<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Reservation;
use App\Models\MeetingRoom;
use App\Models\FoodPackage;
use App\Models\Promotion;
use App\Services\WhatsappNotificationService;
use Illuminate\Http\Request;
use Carbon\Carbon;

class DashboardController extends Controller
{
    public function index(Request $request)
    {
        // Filter status reservasi
        $status = $request->get('status', 'all');

        // Query reservasi dengan filter
        $query = Reservation::with(['meetingRoom', 'foodPackage', 'promotion'])
            ->orderBy('created_at', 'desc');

        if ($status !== 'all') {
            $query->where('status', $status);
        }

        $reservations = $query->paginate(10);

        // Hitung pendapatan hari ini
        $todayRevenue = Reservation::where('status', 'sukses')
            ->whereDate('created_at', today())
            ->get()
            ->sum(function ($reservation) {
                // Hitung diskon berdasarkan persentase
                $discountPercent = $reservation->promotion?->discount ?? 0;
                $discountAmount = ($reservation->total_price * $discountPercent) / 100;
                return $reservation->total_price - $discountAmount;
            });

        // Statistik dashboard
        $stats = [
            'total_reservations' => Reservation::count(),
            'active_rooms' => MeetingRoom::count(),
            'food_packages' => FoodPackage::count(),
            'active_promotions' => Promotion::where('status', true)->count(),
            'today_reservations' => Reservation::whereDate('created_at', today())->count(),
            'today_sukses' => Reservation::where('status', 'sukses')->whereDate('created_at', today())->count(),
            'today_revenue' => $todayRevenue,
        ];

        // Ambil 5 reservasi terbaru untuk widget
        $recentReservations = Reservation::with('meetingRoom')
            ->orderBy('created_at', 'desc')
            ->take(5)
            ->get();

        return view('admin.dashboard.index', compact(
            'reservations',
            'status',
            'stats',
            'recentReservations'
        ));
    }

    public function allReservations(Request $request)
    {
        $query = Reservation::with(['meetingRoom', 'foodPackage', 'promotion']);

        // Filter by room
        $roomId = $request->get('room', 'all');
        if ($roomId !== 'all') {
            $query->where('meeting_room_id', $roomId);
        }

        // Filter by status
        $status = $request->get('status', 'all');
        if ($status !== 'all') {
            $query->where('status', $status);
        }

        // Filter by reschedule status
        $reschedule = $request->get('reschedule', 'all');
        if ($reschedule !== 'all') {
            $query->where('reschedule_status', $reschedule);
        }

        // Filter by date range
        $dateFrom = $request->get('date_from');
        $dateTo = $request->get('date_to');
        if ($dateFrom) {
            $query->whereDate('date', '>=', $dateFrom);
        }
        if ($dateTo) {
            $query->whereDate('date', '<=', $dateTo);
        }

        // Search by customer name or reservation ID
        $search = $request->get('search');
        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('customer_name', 'like', "%{$search}%")
                  ->orWhere('id', 'like', "%{$search}%")
                  ->orWhere('phone', 'like', "%{$search}%");
            });
        }

        // Sort
        $sortBy = $request->get('sort', 'newest');
        switch ($sortBy) {
            case 'oldest':
                $query->orderBy('date', 'asc');
                break;
            case 'date_desc':
                $query->orderBy('date', 'desc');
                break;
            case 'date_asc':
                $query->orderBy('date', 'asc');
                break;
            default: // newest
                $query->orderBy('created_at', 'desc');
                break;
        }

        $reservations = $query->paginate(15)->withQueryString();

        // Get all rooms for filter dropdown
        $rooms = MeetingRoom::orderBy('name')->get();

        return view('admin.dashboard.reservations', compact(
            'reservations',
            'rooms',
            'roomId',
            'status',
            'reschedule',
            'sortBy',
            'dateFrom',
            'dateTo',
            'search'
        ));
    }

    public function show($id)
    {
        $reservation = Reservation::with([
            'meetingRoom',
            'foodPackage',
            'promotion',
            'payment',
            'buffetSelections.buffetMenu'
        ])->findOrFail($id);

        return view('admin.dashboard.show', compact('reservation'));
    }

    public function updateStatus(Request $request, $id)
    {
        $request->validate([
            'status' => 'required|in:pending,sukses,dibatalkan'
        ]);

        $reservation = Reservation::with(['payment', 'meetingRoom', 'foodPackage', 'promotion', 'buffetSelections.buffetMenu'])->findOrFail($id);
        $oldStatus = $reservation->status;
        $reservation->update(['status' => $request->status]);

        // Sinkronkan status pembayaran dengan status reservasi
        if ($reservation->payment) {
            $paymentStatus = match ($request->status) {
                'sukses' => 'sukses',
                'dibatalkan' => 'gagal',
                default => 'pending',
            };

            $reservation->payment->update([
                'payment_status' => $paymentStatus,
            ]);
        }

        // Auto-kirim notifikasi WhatsApp simulasi saat status berubah ke sukses
        if ($request->status === 'sukses' && $oldStatus !== 'sukses' && !$reservation->whatsapp_sent) {
            WhatsappNotificationService::sendAutoNotification($reservation);
        }

        return back()->with('success', 'Status reservasi berhasil diperbarui.');
    }

    public function whatsappSimulation($id)
    {
        $reservation = Reservation::with([
            'meetingRoom',
            'foodPackage',
            'promotion',
            'payment',
            'buffetSelections.buffetMenu',
            'chats' => function($query) {
                $query->orderBy('created_at', 'asc');
            }
        ])->findOrFail($id);

        return view('admin.dashboard.whatsapp-simulation', compact('reservation'));
    }

    /**
     * Helper: Cek konflik sesi untuk admin.
     */
    private function checkAdminSessionConflict($roomId, $date, $newSession, $newPackageName = '', $excludeReservationId = null)
    {
        $existing = Reservation::where('meeting_room_id', $roomId)
            ->where('status', 'sukses')
            ->where(function ($query) use ($date) {
                $query->where('date', $date)
                      ->orWhere(function ($q) use ($date) {
                          $q->where('requested_reschedule_date', $date)
                            ->where('reschedule_status', 'pending');
                      });
            })
            ->when($excludeReservationId, function($q) use ($excludeReservationId) {
                return $q->where('id', '!=', $excludeReservationId);
            })
            ->get();

        if ($existing->isEmpty()) {
            return null;
        }

        foreach ($existing as $res) {
            $isPendingRescheduleToThisDate = $res->reschedule_status === 'pending' && $res->requested_reschedule_date && $res->requested_reschedule_date->format('Y-m-d') === $date;
            $resSession = $isPendingRescheduleToThisDate ? $res->requested_reschedule_session : ($res->time ?? '');
            $resPkg = strtolower($res->foodPackage->name ?? '');

            if (str_contains($resSession, 'Fullboard') || str_contains($resPkg, 'full board') || str_contains($resPkg, 'residential')) {
                return 'Ruangan sudah dipesan untuk sesi Fullboard/Residential pada tanggal ini (kapasitas penuh).';
            }
        }

        $isNewFullboard = str_contains($newSession, 'Fullboard') || str_contains(strtolower($newPackageName), 'full board') || str_contains(strtolower($newPackageName), 'residential');
        if ($isNewFullboard && $existing->count() > 0) {
            return 'Tidak bisa memesan sesi Fullboard/Residential karena sudah ada reservasi lain pada tanggal ini.';
        }

        $existingSessions = $existing->map(function ($res) use ($date) {
            $isPendingRescheduleToThisDate = $res->reschedule_status === 'pending' && $res->requested_reschedule_date && $res->requested_reschedule_date->format('Y-m-d') === $date;
            return $isPendingRescheduleToThisDate ? $res->requested_reschedule_session : $res->time;
        })->toArray();

        foreach ($existingSessions as $s) {
            if ($s === $newSession) {
                return 'Sesi ini sudah dipesan pada tanggal tersebut.';
            }
        }

        if ($existing->count() >= 2) {
            return 'Ruangan sudah memiliki 2 reservasi pada tanggal ini (kapasitas penuh).';
        }

        $hasSiang = collect($existingSessions)->contains(fn($s) => str_contains($s, 'Siang'));
        $hasMalam = collect($existingSessions)->contains(fn($s) => str_contains($s, 'Malam'));
        $newIsSiang = str_contains($newSession, 'Siang');
        $newIsMalam = str_contains($newSession, 'Malam');

        if ($hasSiang && $newIsMalam) {
            return 'Tidak bisa memesan Sesi Malam karena Sesi Siang sudah terisi pada tanggal ini.';
        }
        if ($hasMalam && $newIsSiang) {
            return 'Tidak bisa memesan Sesi Siang karena Sesi Malam sudah terisi pada tanggal ini.';
        }

        return null;
    }

    /**
     * Menyetujui pengajuan reschedule.
     */
    public function approveReschedule($id)
    {
        $reservation = Reservation::with(['meetingRoom', 'foodPackage', 'promotion'])->findOrFail($id);

        if ($reservation->reschedule_status !== 'pending') {
            return back()->with('error', 'Tidak ada pengajuan reschedule aktif untuk reservasi ini.');
        }

        // Cek konflik sesi untuk tanggal/sesi yang diajukan
        $conflictError = $this->checkAdminSessionConflict(
            $reservation->meeting_room_id,
            $reservation->requested_reschedule_date->format('Y-m-d'),
            $reservation->requested_reschedule_session,
            $reservation->foodPackage->name,
            $reservation->id
        );

        if ($conflictError) {
            return back()->with('error', "Gagal menyetujui reschedule: {$conflictError}");
        }

        // Simpan jadwal lama sebelum diperbarui
        $oldDate = $reservation->date->format('Y-m-d');
        $oldSession = $reservation->time;

        // Update jadwal utama
        $reservation->update([
            'date' => $reservation->requested_reschedule_date,
            'time' => $reservation->requested_reschedule_session,
            'reschedule_status' => 'approved',
            'reschedule_count' => $reservation->reschedule_count + 1,
            'requested_reschedule_date' => null,
            'requested_reschedule_session' => null,
            'reschedule_rejection_reason' => null,
            'reschedule_notification_read' => false
        ]);

        // Auto-kirim notifikasi WhatsApp simulasi reschedule
        WhatsappNotificationService::sendRescheduleNotification($reservation, $oldDate, $oldSession);

        return back()->with('success', 'Pengajuan reschedule berhasil disetujui.');
    }

    /**
     * Menolak pengajuan reschedule.
     */
    public function rejectReschedule(Request $request, $id)
    {
        $request->validate([
            'rejection_reason' => 'required|string|max:1000'
        ], [
            'rejection_reason.required' => 'Alasan penolakan wajib diisi.'
        ]);

        $reservation = Reservation::findOrFail($id);

        if ($reservation->reschedule_status !== 'pending') {
            return back()->with('error', 'Tidak ada pengajuan reschedule aktif untuk reservasi ini.');
        }

        $reservation->update([
            'reschedule_status' => 'rejected',
            'reschedule_rejection_reason' => $request->rejection_reason,
            'requested_reschedule_date' => null,
            'requested_reschedule_session' => null,
            'reschedule_notification_read' => false
        ]);

        return back()->with('success', 'Pengajuan reschedule berhasil ditolak.');
    }
}
