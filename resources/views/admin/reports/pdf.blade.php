<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Transaksi - Amaze Hotel</title>
    <style>
        * { margin: 0; padding: 0; box-sizing: border-box; }
        body { font-family: 'DejaVu Sans', sans-serif; font-size: 11px; color: #333; }

        .header { text-align: center; margin-bottom: 25px; padding-bottom: 15px; border-bottom: 3px solid #d97706; }
        .header h1 { font-size: 20px; color: #92400e; margin-bottom: 4px; }
        .header h2 { font-size: 14px; color: #b45309; font-weight: normal; margin-bottom: 8px; }
        .header .period { font-size: 11px; color: #666; }
        .header .room-filter { font-size: 12px; color: #92400e; font-weight: bold; margin-top: 4px; }

        .summary-section { margin-bottom: 20px; }
        .summary-section h3 { font-size: 13px; color: #92400e; margin-bottom: 10px; border-bottom: 1px solid #fbbf24; padding-bottom: 5px; }

        .summary-grid { width: 100%; margin-bottom: 15px; }
        .summary-grid td { padding: 8px 12px; vertical-align: top; }

        .room-card { display: inline-block; width: 30%; background: #fffbeb; border: 1px solid #fbbf24; border-radius: 6px; padding: 10px; margin-right: 2%; margin-bottom: 10px; vertical-align: top; }
        .room-card .room-name { font-weight: bold; color: #92400e; font-size: 11px; }
        .room-card .room-revenue { font-size: 14px; font-weight: bold; color: #b45309; margin-top: 4px; }
        .room-card .room-info { font-size: 9px; color: #666; margin-top: 2px; }

        .total-card { background: #065f46; color: white; border-radius: 6px; padding: 12px 16px; margin-bottom: 20px; }
        .total-card .label { font-size: 10px; opacity: 0.8; }
        .total-card .amount { font-size: 18px; font-weight: bold; }

        table.data-table { width: 100%; border-collapse: collapse; margin-top: 10px; }
        table.data-table th { background: #92400e; color: white; padding: 8px 10px; text-align: left; font-size: 10px; text-transform: uppercase; letter-spacing: 0.5px; }
        table.data-table td { padding: 7px 10px; border-bottom: 1px solid #e5e7eb; font-size: 10px; }
        table.data-table tr:nth-child(even) { background: #fef3c7; }
        table.data-table tr:hover { background: #fde68a; }

        .status-sukses { color: #065f46; font-weight: bold; }
        .status-pending { color: #92400e; font-weight: bold; }
        .status-dibatalkan { color: #991b1b; font-weight: bold; }

        .footer { margin-top: 30px; text-align: center; font-size: 9px; color: #999; border-top: 1px solid #e5e7eb; padding-top: 10px; }

        .page-break { page-break-after: always; }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>AMAZE HOTEL KEDIRI</h1>
        <h2>Laporan Transaksi Meeting Room</h2>
        @if($roomId != 'all')
            <div class="room-filter">{{ $selectedRoomName }}</div>
        @endif
        <div class="period">
            Periode: {{ \Carbon\Carbon::parse($startDate)->locale('id')->isoFormat('D MMMM Y') }}
            s/d {{ \Carbon\Carbon::parse($endDate)->locale('id')->isoFormat('D MMMM Y') }}
        </div>
    </div>

    <!-- Total Revenue -->
    <div class="total-card">
        <div class="label">{{ $roomId != 'all' ? 'Pendapatan ' . $selectedRoomName : 'Total Pendapatan Keseluruhan' }}</div>
        <div class="amount">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</div>
    </div>

    <!-- Per Room Revenue (only when all rooms) -->
    @if($roomId == 'all')
    <div class="summary-section">
        <h3>Pendapatan Per Ruangan</h3>
        <table class="summary-grid">
            <tr>
                @foreach($revenuePerRoom as $index => $data)
                    <td style="width: 33%;">
                        <div class="room-card" style="width: 100%; margin-right: 0;">
                            <div class="room-name">{{ $data['room']->name }}</div>
                            <div class="room-revenue">Rp{{ number_format($data['revenue'], 0, ',', '.') }}</div>
                            <div class="room-info">{{ $data['booking_count'] }} booking sukses
                                @if($totalRevenue > 0)
                                    • {{ number_format(($data['revenue'] / $totalRevenue) * 100, 1) }}%
                                @endif
                            </div>
                        </div>
                    </td>
                    @if(($index + 1) % 3 == 0 && !$loop->last)
                        </tr><tr>
                    @endif
                @endforeach
            </tr>
        </table>
    </div>
    @endif

    <!-- Transaction Details -->
    <div class="summary-section">
        <h3>Detail Transaksi ({{ $reservations->count() }} transaksi)</h3>

        @if($reservations->count() > 0)
            <table class="data-table">
                <thead>
                    <tr>
                        <th style="width: 5%">No</th>
                        <th style="width: 13%">Tanggal</th>
                        <th style="width: 20%">Ruangan</th>
                        <th style="width: 20%">Paket</th>
                        <th style="width: 8%">Peserta</th>
                        <th style="width: 18%">Total</th>
                        <th style="width: 12%">Status</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($reservations as $index => $res)
                        @php
                            $discountPercent = $res->promotion?->discount ?? 0;
                            $discountAmount = ($res->total_price * $discountPercent) / 100;
                            $finalPrice = $res->total_price - $discountAmount;
                        @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ \Carbon\Carbon::parse($res->date)->format('d/m/Y') }}</td>
                            <td>{{ $res->meetingRoom->name }}</td>
                            <td>{{ $res->foodPackage->name }}</td>
                            <td style="text-align: center;">{{ $res->participants }}</td>
                            <td>Rp{{ number_format($finalPrice, 0, ',', '.') }}</td>
                            <td>
                                @if($res->status == 'sukses')
                                    <span class="status-sukses">Sukses</span>
                                @elseif($res->status == 'pending')
                                    <span class="status-pending">Pending</span>
                                @else
                                    <span class="status-dibatalkan">Dibatalkan</span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <p style="text-align: center; color: #999; padding: 30px 0;">Tidak ada transaksi dalam periode ini.</p>
        @endif
    </div>

    <!-- Footer -->
    <div class="footer">
        Dicetak pada {{ now()->locale('id')->isoFormat('D MMMM Y, HH:mm') }} WIB — Amaze Hotel Kediri
    </div>
</body>
</html>
