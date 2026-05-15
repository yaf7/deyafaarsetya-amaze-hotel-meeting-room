<?php

namespace App\Http\Controllers\Customer;

use App\Http\Controllers\Controller;
use App\Models\MeetingRoom;
use App\Models\FoodPackage;
use App\Models\Promotion;
use App\Models\Reservation;
use App\Models\Payment;
use App\Models\BuffetMenu;
use App\Models\ReservationBuffetSelection;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ReservationController extends Controller
{
    /**
     * Menampilkan form reservasi dengan daftar paket dan promo.
     */
    public function showForm($id)
    {
        $room = MeetingRoom::findOrFail($id);
        $packages = FoodPackage::all();
        $promotions = Promotion::where('status', true)->get();

        return view('customer.reservation', compact('room', 'packages', 'promotions'));
    }

    /**
     * Menyimpan reservasi dari form.
     */
    public function store(Request $request)
    {
        $request->validate([
            'customer_name' => 'required|string|max:255',
            'phone' => 'required|string|max:20',
            'date' => 'required|date|after_or_equal:today',
            'time' => 'required',
            'participants' => 'required|integer|min:1',
            'layout' => 'required|in:theater,classroom,round_table,u_shape',
            'food_package_id' => 'required|exists:food_packages,id',
            'promotion_id' => 'nullable|exists:promotions,id',
        ]);

        $room = MeetingRoom::findOrFail($request->room_id);

        // Cek double booking: hanya cek reservasi yang sudah dibayar (sukses)
        $existingReservation = Reservation::where('meeting_room_id', $room->id)
            ->where('date', $request->date)
            ->where('status', 'sukses')
            ->exists();

        if ($existingReservation) {
            return back()->withErrors([
                'date' => "Ruangan {$room->name} sudah dipesan pada tanggal tersebut. Silakan pilih tanggal lain."
            ])->withInput();
        }

        // ✅ Perbaikan utama: langsung akses array (tanpa json_decode)
        $layoutCap = $room->layout[$request->layout] ?? 0;

        if ($request->participants > $layoutCap) {
            return back()->withErrors([
                'participants' => "Peserta melebihi kapasitas layout {$request->layout} (maksimal {$layoutCap} orang)"
            ])->withInput();
        }

        // Simpan data reservasi ke session untuk digunakan setelah buffet selection
        session([
            'reservation_data' => [
                'room_id' => $request->room_id,
                'customer_name' => $request->customer_name,
                'phone' => $request->phone,
                'food_package_id' => $request->food_package_id,
                'promotion_id' => $request->promotion_id,
                'date' => $request->date,
                'time' => $request->time,
                'participants' => $request->participants,
                'layout' => $request->layout,
            ]
        ]);

        // Redirect ke halaman buffet selection
        return redirect()->route('reservation.buffet.selection');
    }

    /**
     * Menampilkan halaman pemilihan menu buffet.
     */
    public function showBuffetSelection()
    {
        // Pastikan ada data reservation di session
        if (!session('reservation_data')) {
            return redirect()->route('rooms.index')->with('error', 'Data reservasi tidak ditemukan');
        }

        // Ambil menu buffet berdasarkan kategori
        $menus = [
            'soup' => BuffetMenu::where('category', 'soup')->get(),
            'mie' => BuffetMenu::where('category', 'mie')->get(),
            'ayam' => BuffetMenu::where('category', 'ayam')->get(),
            'ikan' => BuffetMenu::where('category', 'ikan')->get(),
            'sayuran' => BuffetMenu::where('category', 'sayuran')->get(),
            'fritter' => BuffetMenu::where('category', 'fritter')->get(),
        ];

        $reservationData = session('reservation_data');
        $room = MeetingRoom::findOrFail($reservationData['room_id']);
        $package = FoodPackage::findOrFail($reservationData['food_package_id']);

        return view('customer.buffet-selection', compact('menus', 'room', 'package', 'reservationData'));
    }

    /**
     * Menyimpan pilihan menu buffet dan membuat reservation final.
     */
    public function storeBuffetSelection(Request $request)
    {
        // Validasi: harus memilih satu dari setiap kategori
        $request->validate([
            'soup' => 'required|exists:buffet_menus,id',
            'mie' => 'required|exists:buffet_menus,id',
            'ayam' => 'required|exists:buffet_menus,id',
            'ikan' => 'required|exists:buffet_menus,id',
            'sayuran' => 'required|exists:buffet_menus,id',
            'fritter' => 'required|exists:buffet_menus,id',
        ], [
            'soup.required' => 'Anda harus memilih satu menu Soup',
            'mie.required' => 'Anda harus memilih satu menu Mie',
            'ayam.required' => 'Anda harus memilih satu menu Ayam',
            'ikan.required' => 'Anda harus memilih satu menu Ikan',
            'sayuran.required' => 'Anda harus memilih satu menu Sayuran',
            'fritter.required' => 'Anda harus memilih satu menu Fritter',
        ]);

        // Ambil data reservation dari session
        $reservationData = session('reservation_data');
        if (!$reservationData) {
            return redirect()->route('rooms.index')->with('error', 'Data reservasi tidak ditemukan');
        }

        // Double-check: cek lagi apakah ruangan masih tersedia di tanggal tersebut
        // Hanya cek reservasi yang sudah dibayar (sukses)
        $existingReservation = Reservation::where('meeting_room_id', $reservationData['room_id'])
            ->where('date', $reservationData['date'])
            ->where('status', 'sukses')
            ->exists();

        if ($existingReservation) {
            $room = MeetingRoom::find($reservationData['room_id']);
            session()->forget('reservation_data');
            return redirect()->route('rooms.index')->with('error',
                "Maaf, ruangan {$room->name} sudah dipesan pada tanggal tersebut oleh orang lain. Silakan pilih tanggal lain."
            );
        }

        // Hitung total price
        $package = FoodPackage::findOrFail($reservationData['food_package_id']);
        $total = $package->price * $reservationData['participants'];

        // Buat reservation
        $reservation = Reservation::create([
            'meeting_room_id' => $reservationData['room_id'],
            'food_package_id' => $reservationData['food_package_id'],
            'promotion_id' => $reservationData['promotion_id'] ?: null,
            'admin_id' => 1,
            'customer_name' => $reservationData['customer_name'],
            'phone' => $reservationData['phone'],
            'date' => $reservationData['date'],
            'time' => $reservationData['time'],
            'participants' => $reservationData['participants'],
            'total_price' => $total,
            'status' => 'pending'
        ]);

        // Simpan pilihan buffet
        $buffetSelections = [
            $request->soup,
            $request->mie,
            $request->ayam,
            $request->ikan,
            $request->sayuran,
            $request->fritter,
        ];

        foreach ($buffetSelections as $menuId) {
            ReservationBuffetSelection::create([
                'reservation_id' => $reservation->id,
                'buffet_menu_id' => $menuId,
            ]);
        }

        // Hitung diskon
        $discountPercent = 0;
        if ($reservationData['promotion_id']) {
            $promo = \App\Models\Promotion::find($reservationData['promotion_id']);
            $discountPercent = $promo ? $promo->discount : 0;
        }
        $discountAmount = ($total * $discountPercent) / 100;
        $grossAmount = (int) ($total - $discountAmount);

        // Buat payment
        Payment::create([
            'reservation_id' => $reservation->id,
            'payment_status' => 'pending',
            'gross_amount' => $grossAmount
        ]);

        // Hapus data dari session
        session()->forget('reservation_data');

        return redirect()->route('reservation.confirmation', $reservation->id);
    }

    /**
     * Menampilkan halaman konfirmasi reservasi.
     */
    public function confirmation($id)
    {
        $reservation = Reservation::with([
            'meetingRoom',
            'foodPackage',
            'promotion',
            'payment',
            'buffetSelections.buffetMenu'
        ])->findOrFail($id);

        // Cek apakah sudah expired (20 menit) dan belum bayar
        if ($reservation->status === 'pending' && $reservation->payment && $reservation->payment->payment_status === 'pending') {
            $expiresAt = $reservation->created_at->copy()->addMinutes(20);
            if (now()->greaterThanOrEqualTo($expiresAt)) {
                $reservation->buffetSelections()->delete();
                $reservation->payment()->delete();
                $reservation->delete();

                return redirect()->route('home')->with('error', 'Waktu pembayaran telah habis (20 menit). Reservasi dibatalkan secara otomatis. Silakan buat reservasi baru.');
            }
        }

        // Hitung diskon berdasarkan persentase
        $discountPercent = $reservation->promotion?->discount ?? 0;
        $discountAmount = ($reservation->total_price * $discountPercent) / 100;
        $totalAfterDiscount = $reservation->total_price - $discountAmount;

        return view('customer.confirmation', compact('reservation', 'discountAmount', 'totalAfterDiscount'));
    }

    /**
     * Simulasi pembayaran (tanpa Midtrans).
     */
    public function simulatePayment($id)
    {
        $reservation = Reservation::with('payment')->findOrFail($id);

        if ($reservation->payment && $reservation->payment->payment_status === 'sukses') {
            return redirect()->route('reservation.invoice', $reservation->id);
        }

        // Hitung diskon berdasarkan persentase
        $discountPercent = $reservation->promotion?->discount ?? 0;
        $discountAmount = ($reservation->total_price * $discountPercent) / 100;
        $totalAfterDiscount = $reservation->total_price - $discountAmount;

        $payment = $reservation->payment;
        if ($payment) {
            $payment->update([
                'payment_method' => 'simulasi',
                'payment_status' => 'sukses',
                'transaction_id' => 'SIM-' . strtoupper(uniqid())
            ]);
        }

        $reservation->update(['status' => 'sukses']);

        return redirect()->route('reservation.invoice', $reservation->id);
    }

    /**
     * Menampilkan invoice setelah pembayaran sukses.
     */
    public function invoice($id)
    {
        $reservation = Reservation::with([
            'meetingRoom',
            'foodPackage',
            'promotion',
            'payment'
        ])->findOrFail($id);

        return view('customer.invoice', compact('reservation'));
    }

    /**
     * API: Cek ketersediaan ruangan pada tanggal tertentu.
     */
    public function checkAvailability(Request $request)
    {
        $request->validate([
            'room_id' => 'required|exists:meeting_rooms,id',
            'date' => 'required|date',
        ]);

        $isBooked = Reservation::where('meeting_room_id', $request->room_id)
            ->where('date', $request->date)
            ->where('status', 'sukses')
            ->exists();

        return response()->json([
            'available' => !$isBooked,
            'message' => $isBooked
                ? 'Ruangan sudah dipesan pada tanggal ini. Silakan pilih tanggal lain.'
                : 'Ruangan tersedia pada tanggal ini.'
        ]);
    }

    /**
     * API: Ambil semua tanggal yang sudah dipesan untuk ruangan tertentu.
     */
    public function getBookedDates($roomId)
    {
        $bookedDates = Reservation::where('meeting_room_id', $roomId)
            ->where('status', 'sukses')
            ->where('date', '>=', now()->toDateString())
            ->pluck('date')
            ->map(fn($date) => $date->format('Y-m-d'))
            ->toArray();

        return response()->json(['booked_dates' => $bookedDates]);
    }
}
