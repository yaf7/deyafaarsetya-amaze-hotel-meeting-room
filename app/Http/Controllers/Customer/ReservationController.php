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
     * Helper: Cek konflik sesi untuk ruangan pada tanggal tertentu.
     * Rules:
     * - Maks 2 reservasi/hari: hanya kombinasi Pagi+Siang ATAU Pagi+Malam
     * - Siang & Malam TIDAK boleh bersamaan
     * - Fullboard/Residential mengunci ruangan (maks 1 reservasi)
     *
     * @return string|null Pesan error jika konflik, null jika OK
     */
    private function checkSessionConflict($roomId, $date, $newSession, $newPackageName = '')
    {
        // Ambil semua reservasi aktif (sukses) untuk ruangan ini di tanggal ini
        $existing = Reservation::where('meeting_room_id', $roomId)
            ->where('date', $date)
            ->where('status', 'sukses')
            ->get();

        if ($existing->isEmpty()) {
            return null; // Tidak ada konflik
        }

        // Cek apakah sudah ada reservasi Fullboard/Residential di tanggal ini
        foreach ($existing as $res) {
            $resSession = $res->time ?? '';
            $resPkg = strtolower($res->foodPackage->name ?? '');

            if (str_contains($resSession, 'Fullboard') || str_contains($resPkg, 'full board') || str_contains($resPkg, 'residential')) {
                return 'Ruangan sudah dipesan untuk sesi Fullboard/Residential pada tanggal ini (kapasitas penuh). Silakan pilih tanggal lain.';
            }
        }

        // Jika pesanan baru adalah Fullboard atau Residential, tolak jika sudah ada reservasi apapun
        $isNewFullboard = str_contains($newSession, 'Fullboard') || str_contains(strtolower($newPackageName), 'full board') || str_contains(strtolower($newPackageName), 'residential');
        if ($isNewFullboard && $existing->count() > 0) {
            return 'Tidak bisa memesan sesi Fullboard/Residential karena sudah ada reservasi lain pada tanggal ini. Silakan pilih tanggal lain.';
        }

        // Ambil sesi-sesi yang sudah ada
        $existingSessions = $existing->pluck('time')->toArray();

        // Cek apakah sesi yang sama sudah ada
        foreach ($existingSessions as $s) {
            if ($s === $newSession) {
                return 'Sesi ini sudah dipesan pada tanggal tersebut. Silakan pilih sesi lain.';
            }
        }

        // Sudah ada 2 reservasi? Tolak
        if ($existing->count() >= 2) {
            return 'Ruangan sudah memiliki 2 reservasi pada tanggal ini (kapasitas penuh). Silakan pilih tanggal lain.';
        }

        // Rule: Siang dan Malam tidak boleh bersamaan
        $hasSiang = collect($existingSessions)->contains(fn($s) => str_contains($s, 'Siang'));
        $hasMalam = collect($existingSessions)->contains(fn($s) => str_contains($s, 'Malam'));
        $newIsSiang = str_contains($newSession, 'Siang');
        $newIsMalam = str_contains($newSession, 'Malam');

        if ($hasSiang && $newIsMalam) {
            return 'Tidak bisa memesan Sesi Malam karena Sesi Siang sudah terisi pada tanggal ini. Silakan pilih tanggal lain.';
        }
        if ($hasMalam && $newIsSiang) {
            return 'Tidak bisa memesan Sesi Siang karena Sesi Malam sudah terisi pada tanggal ini. Silakan pilih tanggal lain.';
        }

        return null; // OK
    }
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
            'date' => 'required|date|after_or_equal:today',
            'session_slot' => 'required|in:Sesi Pagi (08:00 - 12:00),Sesi Siang (14:00 - 18:00),Sesi Malam (18:00 - 22:00),Sesi Fullboard (Seharian Penuh)',
            'participants' => 'required|integer|min:1',
            'layout' => 'required|in:theater,classroom,round_table,u_shape',
            'food_package_id' => 'required|exists:food_packages,id',
            'promotion_id' => 'nullable|exists:promotions,id',
            'residential_type' => 'nullable|in:twin,single',
        ]);

        $room = MeetingRoom::findOrFail($request->room_id);

        // Cek konflik sesi berdasarkan aturan bisnis
        $package = FoodPackage::findOrFail($request->food_package_id);
        $conflictError = $this->checkSessionConflict($room->id, $request->date, $request->session_slot, $package->name);

        if ($conflictError) {
            return back()->withErrors([
                'session_slot' => $conflictError
            ])->withInput();
        }

        // ✅ Perbaikan utama: langsung akses array (tanpa json_decode)
        $layoutCap = $room->layout[$request->layout] ?? 0;

        if ($request->participants > $layoutCap) {
            return back()->withErrors([
                'participants' => "Peserta melebihi kapasitas layout {$request->layout} (maksimal {$layoutCap} orang)"
            ])->withInput();
        }

        $isResidential = str_contains(strtolower($package->name), 'residential');
        $residentialType = $isResidential ? $request->residential_type : null;

        // Simpan data reservasi ke session untuk digunakan setelah buffet selection
        session([
            'reservation_data' => [
                'room_id' => $request->room_id,
                'customer_id' => $customer->id,
                'customer_name' => $customer->name,
                'phone' => $customer->phone ?? '-',
                'food_package_id' => $request->food_package_id,
                'promotion_id' => $request->promotion_id,
                'date' => $request->date,
                'time' => $request->session_slot,
                'participants' => $request->participants,
                'layout' => $request->layout,
                'residential_type' => $residentialType,
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

        // Double-check: cek lagi konflik sesi sebelum membuat reservasi
        $package = FoodPackage::findOrFail($reservationData['food_package_id']);
        $conflictError = $this->checkSessionConflict(
            $reservationData['room_id'],
            $reservationData['date'],
            $reservationData['time'],
            $package->name
        );

        if ($conflictError) {
            $room = MeetingRoom::find($reservationData['room_id']);
            session()->forget('reservation_data');
            return redirect()->route('rooms.index')->with('error',
                "Maaf, {$conflictError}"
            );
        }

        // Hitung total price dengan rumus flat untuk paket residential
        $package = FoodPackage::findOrFail($reservationData['food_package_id']);
        $participants = $reservationData['participants'];
        $residentialType = $reservationData['residential_type'] ?? null;
        $packageName = strtolower($package->name);

        if (str_contains($packageName, 'residential full day') && $residentialType === 'twin') {
            // Residential Full Day (Twin): (235.000 × Peserta) + 315.000
            $total = (235000 * $participants) + 315000;
        } elseif (str_contains($packageName, 'residential full day') && $residentialType === 'single') {
            // Residential Full Day (Single): (235.000 × Peserta) + 415.000
            $total = (235000 * $participants) + 415000;
        } elseif (str_contains($packageName, 'residential full board') && $residentialType === 'twin') {
            // Residential Full Board (Twin): (380.000 × Peserta) + 220.000
            $total = (380000 * $participants) + 220000;
        } elseif (str_contains($packageName, 'residential full board') && $residentialType === 'single') {
            // Residential Full Board (Single): (380.000 × Peserta) + 320.000
            $total = (380000 * $participants) + 320000;
        } else {
            // Paket Non-Residential: Total = Harga Paket × Jumlah Peserta
            $total = $package->price * $participants;
        }

        // Buat reservation
        $reservation = Reservation::create([
            'meeting_room_id' => $reservationData['room_id'],
            'food_package_id' => $reservationData['food_package_id'],
            'promotion_id' => $reservationData['promotion_id'] ?: null,
            'admin_id' => 1, // Default admin, bisa diubah jika perlu
            'customer_id' => $reservationData['customer_id'] ?? null,
            'customer_name' => $reservationData['customer_name'],
            'phone' => $reservationData['phone'],
            'date' => $reservationData['date'],
            'time' => $reservationData['time'],
            'participants' => $reservationData['participants'],
            'layout' => $reservationData['layout'] ?? null,
            'residential_type' => $reservationData['residential_type'] ?? null,
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

        // Ambil semua reservasi aktif di tanggal ini
        $existing = Reservation::where('meeting_room_id', $request->room_id)
            ->where('date', $request->date)
            ->where('status', 'sukses')
            ->get();

        // Cek apakah masih ada slot tersedia
        $fullyBooked = false;
        $message = 'Ruangan tersedia pada tanggal ini.';
        $bookedSessions = [];

        if ($existing->count() > 0) {
            foreach ($existing as $res) {
                $resSession = $res->time ?? '';
                $resPkg = strtolower($res->foodPackage->name ?? '');
                $bookedSessions[] = $resSession;

                // Jika ada Fullboard/Residential, ruangan penuh
                if (str_contains($resSession, 'Fullboard') || str_contains($resPkg, 'full board') || str_contains($resPkg, 'residential')) {
                    $fullyBooked = true;
                    $message = 'Ruangan sudah dipesan untuk sesi Fullboard/Residential pada tanggal ini.';
                    break;
                }
            }

            // Jika sudah 2 reservasi, penuh
            if (!$fullyBooked && $existing->count() >= 2) {
                $fullyBooked = true;
                $message = 'Ruangan sudah memiliki 2 reservasi pada tanggal ini.';
            }
        }

        return response()->json([
            'available' => !$fullyBooked,
            'message' => $message,
            'booked_sessions' => $bookedSessions,
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
