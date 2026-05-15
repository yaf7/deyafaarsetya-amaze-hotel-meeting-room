@extends('admin.layouts.app')

@section('page-title', 'Laporan Transaksi')

@section('content')
<!-- Header -->
<div class="mb-6">
    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Laporan Transaksi</h1>
    <p class="text-gray-500 text-sm">Lihat dan filter riwayat transaksi per ruangan</p>
</div>

<!-- Filter Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 sm:p-5 mb-6">
    <form method="GET">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4">
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Dari Tanggal</label>
                <input type="date" name="start_date" value="{{ $startDate }}"
                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border-2 border-gray-200 rounded-xl focus:border-amber-500 outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Sampai Tanggal</label>
                <input type="date" name="end_date" value="{{ $endDate }}"
                       class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border-2 border-gray-200 rounded-xl focus:border-amber-500 outline-none text-sm">
            </div>
            <div>
                <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Room</label>
                <select name="room_id" class="w-full px-3 sm:px-4 py-2 sm:py-2.5 border-2 border-gray-200 rounded-xl focus:border-amber-500 outline-none text-sm">
                    <option value="all" {{ $roomId == 'all' ? 'selected' : '' }}>Semua Room</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ $roomId == $room->id ? 'selected' : '' }}>{{ $room->name }}</option>
                    @endforeach
                </select>
            </div>
            <div class="flex items-end">
                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white py-2 sm:py-2.5 rounded-xl font-medium transition-colors flex items-center justify-center gap-2 text-sm">
                    <i class="fas fa-filter"></i>
                    <span>Filter</span>
                </button>
            </div>
        </div>
    </form>

    <!-- Export Buttons -->
    <div class="flex flex-wrap gap-2 mt-4 pt-4 border-t border-gray-100">
        <a href="{{ route('admin.reports.export-pdf', ['start_date' => $startDate, 'end_date' => $endDate, 'room_id' => $roomId]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-red-500 hover:bg-red-600 text-white rounded-xl text-sm font-medium transition-colors">
            <i class="fas fa-file-pdf"></i>
            Export PDF {{ $roomId != 'all' ? '(' . $selectedRoomName . ')' : '(Semua)' }}
        </a>
        <a href="{{ route('admin.reports.export-excel', ['start_date' => $startDate, 'end_date' => $endDate, 'room_id' => $roomId]) }}"
           class="inline-flex items-center gap-2 px-4 py-2 bg-green-600 hover:bg-green-700 text-white rounded-xl text-sm font-medium transition-colors">
            <i class="fas fa-file-excel"></i>
            Export Excel {{ $roomId != 'all' ? '(' . $selectedRoomName . ')' : '(Semua)' }}
        </a>
    </div>
</div>

<!-- Revenue Summary -->
<div class="bg-gradient-to-r from-green-500 to-emerald-600 rounded-2xl p-4 sm:p-6 mb-6 text-white">
    <div class="flex items-center gap-3 sm:gap-4">
        <div class="w-12 h-12 sm:w-14 sm:h-14 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
            <i class="fas fa-wallet text-xl sm:text-2xl"></i>
        </div>
        <div>
            <p class="text-white/80 text-xs sm:text-sm">
                {{ $roomId != 'all' ? 'Pendapatan ' . $selectedRoomName : 'Total Pendapatan Keseluruhan' }}
            </p>
            <p class="text-2xl sm:text-3xl font-bold">Rp{{ number_format($totalRevenue, 0, ',', '.') }}</p>
            <p class="text-white/70 text-xs sm:text-sm mt-1">
                {{ \Carbon\Carbon::parse($startDate)->locale('id')->isoFormat('D MMM Y') }}
                - {{ \Carbon\Carbon::parse($endDate)->locale('id')->isoFormat('D MMM Y') }}
            </p>
        </div>
    </div>
</div>

<!-- Per-Room Revenue (only show when "Semua Room" is selected) -->
@if($roomId == 'all')
<div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 mb-6">
    @php
        $roomColors = [
            ['from-amber-500', 'to-orange-600'],
            ['from-blue-500', 'to-indigo-600'],
            ['from-purple-500', 'to-pink-600'],
            ['from-teal-500', 'to-cyan-600'],
            ['from-rose-500', 'to-red-600'],
        ];
        $roomIcons = ['fa-door-open', 'fa-building', 'fa-landmark', 'fa-hotel', 'fa-house'];
    @endphp
    @foreach($revenuePerRoom as $index => $data)
        @php
            $colors = $roomColors[$index % count($roomColors)];
            $icon = $roomIcons[$index % count($roomIcons)];
        @endphp
        <div class="bg-gradient-to-r {{ $colors[0] }} {{ $colors[1] }} rounded-2xl p-4 sm:p-5 text-white">
            <div class="flex items-center gap-3 mb-3">
                <div class="w-10 h-10 bg-white/20 rounded-xl flex items-center justify-center flex-shrink-0">
                    <i class="fas {{ $icon }} text-lg"></i>
                </div>
                <div class="min-w-0">
                    <p class="font-semibold text-sm truncate">{{ $data['room']->name }}</p>
                    <p class="text-white/70 text-xs">{{ $data['booking_count'] }} booking sukses</p>
                </div>
            </div>
            <p class="text-xl sm:text-2xl font-bold">Rp{{ number_format($data['revenue'], 0, ',', '.') }}</p>
            @if($totalRevenue > 0)
                <div class="mt-2">
                    <div class="w-full bg-white/20 rounded-full h-1.5">
                        <div class="bg-white rounded-full h-1.5" style="width: {{ min(($data['revenue'] / $totalRevenue) * 100, 100) }}%"></div>
                    </div>
                    <p class="text-white/70 text-xs mt-1">{{ number_format(($data['revenue'] / $totalRevenue) * 100, 1) }}% dari total</p>
                </div>
            @endif
        </div>
    @endforeach
</div>
@endif

@if($reservations->count() > 0)
    <!-- Mobile: Cards -->
    <div class="block lg:hidden space-y-3">
        @foreach($reservations as $res)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-start justify-between gap-3 mb-3">
                    <div>
                        <div class="flex items-center gap-2 mb-1">
                            <span class="text-xs text-gray-400">#{{ $res->id }}</span>
                            <span class="text-xs text-gray-400">•</span>
                            <span class="text-xs text-gray-500">{{ \Carbon\Carbon::parse($res->date)->locale('id')->isoFormat('D MMM Y') }}</span>
                        </div>
                        <p class="font-medium text-gray-800">{{ $res->meetingRoom->name }}</p>
                        <p class="text-sm text-gray-500">{{ $res->foodPackage->name }} • {{ $res->participants }} peserta</p>
                    </div>
                    @if($res->status == 'sukses')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                            Sukses
                        </span>
                    @elseif($res->status == 'pending')
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                            Pending
                        </span>
                    @else
                        <span class="inline-flex items-center px-2 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                            Batal
                        </span>
                    @endif
                </div>
                <div class="pt-3 border-t border-gray-100">
                    @php
                        $discountPercent = $res->promotion?->discount ?? 0;
                        $discountAmount = ($res->total_price * $discountPercent) / 100;
                    @endphp
                    <span class="font-bold text-gray-800">Rp{{ number_format($res->total_price - $discountAmount, 0, ',', '.') }}</span>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Desktop: Table -->
    <div class="hidden lg:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Tanggal</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Ruang</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Paket</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Peserta</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Status</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($reservations as $res)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-gray-100 rounded-xl flex items-center justify-center">
                                        <span class="text-xs font-bold text-gray-500">#{{ $res->id }}</span>
                                    </div>
                                    <span class="text-gray-600">{{ \Carbon\Carbon::parse($res->date)->locale('id')->isoFormat('D MMM Y') }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6 font-medium text-gray-800">{{ $res->meetingRoom->name }}</td>
                            <td class="py-4 px-6 text-gray-600">{{ $res->foodPackage->name }}</td>
                            <td class="py-4 px-6">
                                <span class="inline-flex items-center px-2.5 py-1 rounded-lg bg-gray-100 text-gray-600 text-sm">
                                    <i class="fas fa-users mr-1 text-xs"></i>
                                    {{ $res->participants }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                @php
                                    $discountPercent = $res->promotion?->discount ?? 0;
                                    $discountAmount = ($res->total_price * $discountPercent) / 100;
                                @endphp
                                <span class="font-bold text-gray-800">Rp{{ number_format($res->total_price - $discountAmount, 0, ',', '.') }}</span>
                            </td>
                            <td class="py-4 px-6">
                                @if($res->status == 'sukses')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-green-100 text-green-700">
                                        <i class="fas fa-check mr-1"></i> Sukses
                                    </span>
                                @elseif($res->status == 'pending')
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-yellow-100 text-yellow-700">
                                        <i class="fas fa-clock mr-1"></i> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-100 text-red-700">
                                        <i class="fas fa-times mr-1"></i> Dibatalkan
                                    </span>
                                @endif
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-inbox text-gray-400 text-2xl sm:text-3xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Tidak Ada Transaksi</h3>
        <p class="text-gray-500 text-sm">Tidak ada transaksi dalam periode yang dipilih</p>
    </div>
@endif
@endsection
