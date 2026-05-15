<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithMapping;
use Maatwebsite\Excel\Concerns\WithStyles;
use Maatwebsite\Excel\Concerns\WithColumnWidths;
use Maatwebsite\Excel\Concerns\WithTitle;
use PhpOffice\PhpSpreadsheet\Worksheet\Worksheet;
use Illuminate\Support\Collection;

class ReservationExport implements FromCollection, WithHeadings, WithMapping, WithStyles, WithColumnWidths, WithTitle
{
    protected $reservations;
    protected $totalRevenue;
    protected $revenuePerRoom;
    protected $startDate;
    protected $endDate;
    protected $roomName;

    public function __construct($reservations, $totalRevenue, $revenuePerRoom, $startDate, $endDate, $roomName = 'Semua Room')
    {
        $this->reservations = $reservations;
        $this->totalRevenue = $totalRevenue;
        $this->revenuePerRoom = $revenuePerRoom;
        $this->startDate = $startDate;
        $this->endDate = $endDate;
        $this->roomName = $roomName;
    }

    public function title(): string
    {
        return 'Laporan ' . $this->roomName;
    }

    public function collection(): Collection
    {
        // Add reservation rows
        $rows = $this->reservations->map(function ($res) {
            return $res;
        });

        // Add empty separator row
        $rows->push(null);

        // Add per-room summary rows
        foreach ($this->revenuePerRoom as $data) {
            $rows->push((object) [
                'id' => '',
                'date' => '',
                'meetingRoom' => (object) ['name' => $data['room']->name],
                'foodPackage' => (object) ['name' => ''],
                'participants' => $data['booking_count'] . ' booking',
                'total_price' => $data['revenue'],
                'promotion' => null,
                'status' => 'PENDAPATAN ROOM',
                '_is_summary' => true,
                '_revenue' => $data['revenue'],
            ]);
        }

        // Add total row
        $rows->push((object) [
            'id' => '',
            'date' => '',
            'meetingRoom' => (object) ['name' => 'TOTAL ' . strtoupper($this->roomName)],
            'foodPackage' => (object) ['name' => ''],
            'participants' => '',
            'total_price' => $this->totalRevenue,
            'promotion' => null,
            'status' => '',
            '_is_summary' => true,
            '_revenue' => $this->totalRevenue,
        ]);

        return $rows;
    }

    public function headings(): array
    {
        return [
            'No',
            'Tanggal',
            'Ruangan',
            'Paket',
            'Peserta',
            'Total (Rp)',
            'Status',
        ];
    }

    public function map($row): array
    {
        if ($row === null) {
            return ['', '', '', '', '', '', ''];
        }

        if (isset($row->_is_summary) && $row->_is_summary) {
            return [
                '',
                '',
                $row->meetingRoom->name,
                '',
                is_string($row->participants) ? $row->participants : '',
                number_format($row->_revenue, 0, ',', '.'),
                $row->status,
            ];
        }

        $discountPercent = $row->promotion?->discount ?? 0;
        $discountAmount = ($row->total_price * $discountPercent) / 100;
        $finalPrice = $row->total_price - $discountAmount;

        return [
            $row->id,
            \Carbon\Carbon::parse($row->date)->format('d/m/Y'),
            $row->meetingRoom->name ?? '-',
            $row->foodPackage->name ?? '-',
            $row->participants,
            number_format($finalPrice, 0, ',', '.'),
            ucfirst($row->status),
        ];
    }

    public function columnWidths(): array
    {
        return [
            'A' => 6,
            'B' => 14,
            'C' => 22,
            'D' => 22,
            'E' => 10,
            'F' => 18,
            'G' => 14,
        ];
    }

    public function styles(Worksheet $sheet): array
    {
        return [
            1 => [
                'font' => ['bold' => true, 'size' => 11],
                'fill' => [
                    'fillType' => \PhpOffice\PhpSpreadsheet\Style\Fill::FILL_SOLID,
                    'startColor' => ['argb' => 'FF4472C4'],
                ],
                'font' => ['bold' => true, 'color' => ['argb' => 'FFFFFFFF']],
            ],
        ];
    }
}
