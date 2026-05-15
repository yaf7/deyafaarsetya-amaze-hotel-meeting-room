@extends('admin.layouts.app')

@section('page-title', 'Semua Reservasi')

@section('content')
<div class="mb-6">
    <div class="flex items-center gap-2 text-sm text-gray-500 mb-2">
        <a href="{{ route('admin.dashboard') }}" class="hover:text-amber-600 transition">Dashboard</a>
        <i class="fas fa-chevron-right text-xs"></i>
        <span class="text-gray-800 font-medium">Semua Reservasi</span>
    </div>
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h1 class="text-2xl font-bold text-gray-800">Semua Reservasi</h1>
            <p class="text-gray-500 text-sm mt-1">Kelola dan pantau semua reservasi meeting room</p>
        </div>
        <div class="flex items-center gap-2">
            <span class="bg-amber-100 text-amber-800 text-sm font-semibold px-3 py-1.5 rounded-lg">
                <i class="fas fa-calendar-check mr-1"></i>
                {{ $reservations->total() }} Reservasi
            </span>
        </div>
    </div>
</div>

<!-- Filter & Search Section -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 p-5 mb-6">
    <form method="GET" action="{{ route('admin.reservations') }}" id="filterForm">
        <!-- Search Bar -->
        <div class="mb-4">
            <div class="relative">
                <i class="fas fa-search absolute left-3 top-1/2 -translate-y-1/2 text-gray-400"></i>
                <input type="text" name="search" value="{{ $search ?? '' }}"
                    placeholder="Cari nama pelanggan, no. reservasi, atau no. telepon..."
                    class="w-full pl-10 pr-4 py-2.5 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-300 focus:border-amber-400 transition text-sm bg-gray-50 focus:bg-white">
            </div>
        </div>

        <!-- Filter Row -->
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-5 gap-3">
            <!-- Room Filter -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Ruang Meeting</label>
                <select name="room" onchange="document.getElementById('filterForm').submit()"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-300 focus:border-amber-400 bg-white">
                    <option value="all">Semua Ruang</option>
                    @foreach($rooms as $room)
                        <option value="{{ $room->id }}" {{ $roomId == $room->id ? 'selected' : '' }}>
                            {{ $room->name }}
                        </option>
                    @endforeach
                </select>
            </div>

            <!-- Status Filter -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Status</label>
                <select name="status" onchange="document.getElementById('filterForm').submit()"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-300 focus:border-amber-400 bg-white">
                    <option value="all" {{ $status == 'all' ? 'selected' : '' }}>Semua Status</option>
                    <option value="pending" {{ $status == 'pending' ? 'selected' : '' }}>Pending</option>
                    <option value="sukses" {{ $status == 'sukses' ? 'selected' : '' }}>Sukses</option>
                    <option value="dibatalkan" {{ $status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                </select>
            </div>

            <!-- Date From -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Dari Tanggal</label>
                <input type="date" name="date_from" value="{{ $dateFrom ?? '' }}"
                    onchange="document.getElementById('filterForm').submit()"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-300 focus:border-amber-400 bg-white">
            </div>

            <!-- Date To -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Sampai Tanggal</label>
                <input type="date" name="date_to" value="{{ $dateTo ?? '' }}"
                    onchange="document.getElementById('filterForm').submit()"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-300 focus:border-amber-400 bg-white">
            </div>

            <!-- Sort -->
            <div>
                <label class="block text-xs font-semibold text-gray-500 uppercase tracking-wider mb-1.5">Urutkan</label>
                <select name="sort" onchange="document.getElementById('filterForm').submit()"
                    class="w-full border border-gray-200 rounded-lg px-3 py-2 text-sm focus:ring-2 focus:ring-amber-300 focus:border-amber-400 bg-white">
                    <option value="newest" {{ $sortBy == 'newest' ? 'selected' : '' }}>Terbaru Dibuat</option>
                    <option value="oldest" {{ $sortBy == 'oldest' ? 'selected' : '' }}>Terlama Dibuat</option>
                    <option value="date_desc" {{ $sortBy == 'date_desc' ? 'selected' : '' }}>Tanggal Meeting ↓</option>
                    <option value="date_asc" {{ $sortBy == 'date_asc' ? 'selected' : '' }}>Tanggal Meeting ↑</option>
                </select>
            </div>
        </div>

        <!-- Active Filters & Reset -->
        @if($roomId !== 'all' || $status !== 'all' || $dateFrom || $dateTo || $search)
            <div class="mt-4 pt-3 border-t border-gray-100 flex flex-wrap items-center gap-2">
                <span class="text-xs text-gray-500 font-medium">Filter aktif:</span>

                @if($search)
                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs px-2.5 py-1 rounded-full border border-amber-200">
                        <i class="fas fa-search text-[10px]"></i> "{{ $search }}"
                    </span>
                @endif

                @if($roomId !== 'all')
                    @php $selectedRoom = $rooms->firstWhere('id', $roomId); @endphp
                    <span class="inline-flex items-center gap-1 bg-blue-50 text-blue-700 text-xs px-2.5 py-1 rounded-full border border-blue-200">
                        <i class="fas fa-door-open text-[10px]"></i> {{ $selectedRoom->name ?? 'Room' }}
                    </span>
                @endif

                @if($status !== 'all')
                    <span class="inline-flex items-center gap-1 bg-purple-50 text-purple-700 text-xs px-2.5 py-1 rounded-full border border-purple-200">
                        <i class="fas fa-filter text-[10px]"></i> {{ ucfirst($status) }}
                    </span>
                @endif

                @if($dateFrom || $dateTo)
                    <span class="inline-flex items-center gap-1 bg-green-50 text-green-700 text-xs px-2.5 py-1 rounded-full border border-green-200">
                        <i class="fas fa-calendar text-[10px]"></i>
                        {{ $dateFrom ? \Carbon\Carbon::parse($dateFrom)->format('d/m/Y') : '...' }}
                        -
                        {{ $dateTo ? \Carbon\Carbon::parse($dateTo)->format('d/m/Y') : '...' }}
                    </span>
                @endif

                <a href="{{ route('admin.reservations') }}"
                   class="inline-flex items-center gap-1 text-xs text-red-500 hover:text-red-700 font-medium ml-2 transition">
                    <i class="fas fa-times-circle"></i> Reset Filter
                </a>
            </div>
        @endif
    </form>
</div>

<!-- Room Quick Filter Tabs -->
<div class="flex flex-wrap gap-2 mb-5">
    <a href="{{ route('admin.reservations', array_merge(request()->except(['room', 'page']), ['room' => 'all'])) }}"
       class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ $roomId === 'all' ? 'bg-amber-500 text-white shadow-md shadow-amber-200' : 'bg-white text-gray-600 hover:bg-amber-50 hover:text-amber-700 border border-gray-200' }}">
        <i class="fas fa-layer-group mr-1"></i> Semua
    </a>
    @foreach($rooms as $room)
        <a href="{{ route('admin.reservations', array_merge(request()->except(['room', 'page']), ['room' => $room->id])) }}"
           class="px-4 py-2 rounded-full text-sm font-medium transition-all duration-200 {{ $roomId == $room->id ? 'bg-amber-500 text-white shadow-md shadow-amber-200' : 'bg-white text-gray-600 hover:bg-amber-50 hover:text-amber-700 border border-gray-200' }}">
            <i class="fas fa-door-open mr-1"></i> {{ $room->name }}
        </a>
    @endforeach
</div>

<!-- Reservations Table (Desktop) -->
<div class="bg-white rounded-xl shadow-sm border border-gray-100 overflow-hidden hidden md:block">
    @if($reservations->count() > 0)
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead>
                    <tr class="bg-gradient-to-r from-gray-50 to-gray-100 border-b border-gray-200">
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">No. Reservasi</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Pelanggan</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Ruang Meeting</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                        <th class="text-left px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Total</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Status</th>
                        <th class="text-center px-5 py-3.5 text-xs font-bold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($reservations as $res)
                        <tr class="hover:bg-amber-50/30 transition-colors duration-150">
                            <td class="px-5 py-4">
                                <span class="font-bold text-gray-800 text-sm">#{{ str_pad($res->id, 6, '0', STR_PAD_LEFT) }}</span>
                            </td>
                            <td class="px-5 py-4">
                                <div>
                                    <div class="flex items-center gap-2">
                                        <p class="font-semibold text-gray-800 text-sm">{{ $res->customer_name }}</p>
                                        @if($res->whatsapp_sent)
                                            <i class="fab fa-whatsapp text-blue-500 text-[12px]" title="WhatsApp Terkirim"></i>
                                        @endif
                                    </div>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        <i class="fas fa-phone text-[10px] mr-1"></i>{{ $res->phone }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                <span class="inline-flex items-center gap-1.5 text-sm text-gray-700">
                                    <span class="w-2 h-2 rounded-full bg-amber-400"></span>
                                    {{ $res->meetingRoom->name }}
                                </span>
                            </td>
                            <td class="px-5 py-4">
                                <div>
                                    <p class="text-sm font-medium text-gray-800">
                                        {{ \Carbon\Carbon::parse($res->date)->locale('id')->isoFormat('D MMMM Y') }}
                                    </p>
                                    <p class="text-xs text-gray-400 mt-0.5">
                                        <i class="far fa-clock text-[10px] mr-1"></i>{{ $res->time }}
                                    </p>
                                </div>
                            </td>
                            <td class="px-5 py-4">
                                @php
                                    $discountPercent = $res->promotion?->discount ?? 0;
                                    $discountAmount = ($res->total_price * $discountPercent) / 100;
                                    $finalPrice = $res->total_price - $discountAmount;
                                @endphp
                                <p class="font-bold text-gray-800 text-sm">Rp{{ number_format($finalPrice, 0, ',', '.') }}</p>
                                @if($discountPercent > 0)
                                    <p class="text-xs text-red-400 line-through mt-0.5">Rp{{ number_format($res->total_price, 0, ',', '.') }}</p>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                @if($res->status == 'sukses')
                                    <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-emerald-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses
                                    </span>
                                @elseif($res->status == 'pending')
                                    <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-amber-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                    </span>
                                @else
                                    <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-red-200">
                                        <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Dibatalkan
                                    </span>
                                @endif
                            </td>
                            <td class="px-5 py-4 text-center">
                                <a href="{{ route('admin.reservation.show', $res->id) }}"
                                   class="inline-flex items-center gap-1.5 bg-amber-50 text-amber-700 hover:bg-amber-100 text-xs font-semibold px-3 py-1.5 rounded-lg transition border border-amber-200 hover:border-amber-300">
                                    <i class="fas fa-eye text-[10px]"></i> Detail
                                </a>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    @else
        <div class="text-center py-16">
            <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                <i class="fas fa-calendar-xmark text-3xl text-gray-300"></i>
            </div>
            <h3 class="text-lg font-semibold text-gray-600 mb-1">Tidak ada reservasi ditemukan</h3>
            <p class="text-sm text-gray-400">Coba ubah filter atau kata kunci pencarian Anda</p>
            @if($roomId !== 'all' || $status !== 'all' || $dateFrom || $dateTo || $search)
                <a href="{{ route('admin.reservations') }}" class="inline-flex items-center gap-1.5 mt-4 text-amber-600 hover:text-amber-700 font-medium text-sm">
                    <i class="fas fa-arrow-rotate-left"></i> Reset semua filter
                </a>
            @endif
        </div>
    @endif
</div>

<!-- Reservations Cards (Mobile) -->
<div class="md:hidden space-y-3">
    @if($reservations->count() > 0)
        @foreach($reservations as $res)
            <div class="bg-white rounded-xl shadow-sm border border-gray-100 p-4 hover:shadow-md hover:border-amber-200 transition-all duration-200">
                <div class="flex justify-between items-start mb-3">
                    <div>
                        <h3 class="font-bold text-gray-800">#{{ str_pad($res->id, 6, '0', STR_PAD_LEFT) }}</h3>
                        <div class="flex items-center gap-2 mt-0.5">
                            <p class="text-sm text-gray-500">{{ $res->customer_name }}</p>
                            @if($res->whatsapp_sent)
                                <i class="fab fa-whatsapp text-blue-500 text-xs" title="WhatsApp Terkirim"></i>
                            @endif
                        </div>
                    </div>
                    <div class="text-right">
                        @php
                            $discountPercent = $res->promotion?->discount ?? 0;
                            $discountAmount = ($res->total_price * $discountPercent) / 100;
                            $finalPrice = $res->total_price - $discountAmount;
                        @endphp
                        <p class="font-bold text-gray-800">Rp{{ number_format($finalPrice, 0, ',', '.') }}</p>
                    </div>
                </div>

                <div class="flex flex-wrap gap-2 mb-3">
                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-lg">
                        <i class="fas fa-door-open text-[10px]"></i> {{ $res->meetingRoom->name }}
                    </span>
                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-lg">
                        <i class="far fa-calendar text-[10px]"></i>
                        {{ \Carbon\Carbon::parse($res->date)->locale('id')->isoFormat('D MMM Y') }}
                    </span>
                    <span class="inline-flex items-center gap-1 bg-gray-100 text-gray-600 text-xs px-2 py-1 rounded-lg">
                        <i class="far fa-clock text-[10px]"></i> {{ $res->time }}
                    </span>
                </div>

                <div class="flex items-center justify-between">
                    <div>
                        @if($res->status == 'sukses')
                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-emerald-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses
                            </span>
                        @elseif($res->status == 'pending')
                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-amber-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                            </span>
                        @else
                            <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-red-200">
                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Dibatalkan
                            </span>
                        @endif
                    </div>
                    <a href="{{ route('admin.reservation.show', $res->id) }}"
                       class="inline-flex items-center gap-1 text-amber-600 hover:text-amber-800 text-sm font-medium transition">
                        Lihat Detail <i class="fas fa-arrow-right text-xs"></i>
                    </a>
                </div>
            </div>
        @endforeach
    @else
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 text-center py-12">
            <div class="w-16 h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-3">
                <i class="fas fa-calendar-xmark text-2xl text-gray-300"></i>
            </div>
            <h3 class="font-semibold text-gray-600 mb-1">Tidak ada reservasi</h3>
            <p class="text-sm text-gray-400">Coba ubah filter pencarian</p>
        </div>
    @endif
</div>

<!-- Pagination -->
@if($reservations->hasPages())
    <div class="mt-6 flex justify-center">
        <div class="bg-white rounded-xl shadow-sm border border-gray-100 px-4 py-3">
            {{ $reservations->links() }}
        </div>
    </div>
@endif

@endsection
