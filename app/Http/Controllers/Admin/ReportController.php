<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\MeetingRoom;
use App\Models\Reservation;
use App\Exports\ReservationExport;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Maatwebsite\Excel\Facades\Excel;

class ReportController extends Controller
{
    /**
     * Get filtered reservation data (shared logic for index, exportPdf, exportExcel).
     */
    private function getFilteredData(Request $request): array
    {
        $startDate = $request->get('start_date', now()->subMonth()->format('Y-m-d'));
        $endDate = $request->get('end_date', now()->format('Y-m-d'));
        $roomId = $request->get('room_id', 'all');

        if (!\DateTime::createFromFormat('Y-m-d', $startDate) || !\DateTime::createFromFormat('Y-m-d', $endDate)) {
            $startDate = now()->subMonth()->format('Y-m-d');
            $endDate = now()->format('Y-m-d');
        }

        $query = Reservation::with(['meetingRoom', 'foodPackage', 'promotion'])
            ->whereDate('date', '>=', $startDate)
            ->whereDate('date', '<=', $endDate);

        if ($roomId !== 'all') {
            $query->where('meeting_room_id', $roomId);
        }

        $reservations = $query->get();
        $successReservations = $reservations->filter(fn($r) => $r->status === 'sukses');

        $totalRevenue = $successReservations->sum(function ($reservation) {
            $discountPercent = $reservation->promotion?->discount ?? 0;
            $discountAmount = ($reservation->total_price * $discountPercent) / 100;
            return $reservation->total_price - $discountAmount;
        });

        $rooms = MeetingRoom::all();
        $revenuePerRoom = [];

        foreach ($rooms as $room) {
            $roomReservations = $successReservations->where('meeting_room_id', $room->id);
            $roomRevenue = $roomReservations->sum(function ($reservation) {
                $discountPercent = $reservation->promotion?->discount ?? 0;
                $discountAmount = ($reservation->total_price * $discountPercent) / 100;
                return $reservation->total_price - $discountAmount;
            });

            $revenuePerRoom[] = [
                'room' => $room,
                'revenue' => $roomRevenue,
                'booking_count' => $roomReservations->count(),
            ];
        }

        // Nama room yang difilter (untuk judul export)
        $selectedRoomName = 'Semua Room';
        if ($roomId !== 'all') {
            $selectedRoom = $rooms->firstWhere('id', $roomId);
            $selectedRoomName = $selectedRoom ? $selectedRoom->name : 'Semua Room';
        }

        return compact('reservations', 'totalRevenue', 'revenuePerRoom', 'startDate', 'endDate', 'roomId', 'rooms', 'selectedRoomName');
    }

    public function index(Request $request)
    {
        $data = $this->getFilteredData($request);
        return view('admin.reports.index', $data);
    }

    public function exportPdf(Request $request)
    {
        $data = $this->getFilteredData($request);

        $pdf = Pdf::loadView('admin.reports.pdf', $data);
        $pdf->setPaper('A4', 'portrait');

        $roomSlug = $data['roomId'] !== 'all' ? '_' . str_replace(' ', '_', $data['selectedRoomName']) : '';
        $filename = 'Laporan_Transaksi' . $roomSlug . '_' . $data['startDate'] . '_' . $data['endDate'] . '.pdf';
        return $pdf->download($filename);
    }

    public function exportExcel(Request $request)
    {
        $data = $this->getFilteredData($request);

        $roomSlug = $data['roomId'] !== 'all' ? '_' . str_replace(' ', '_', $data['selectedRoomName']) : '';
        $filename = 'Laporan_Transaksi' . $roomSlug . '_' . $data['startDate'] . '_' . $data['endDate'] . '.xlsx';

        return Excel::download(
            new ReservationExport(
                $data['reservations'],
                $data['totalRevenue'],
                $data['revenuePerRoom'],
                $data['startDate'],
                $data['endDate'],
                $data['selectedRoomName']
            ),
            $filename
        );
    }
}
