@extends('admin.layouts.app')

@section('content')
<div class="mb-6">
    <h1 class="text-2xl font-bold text-gray-800">Dashboard Admin</h1>
    <p class="text-gray-600">Amaze Hotel Kediri</p>
</div>

<!-- Statistik Utama -->
<div class="grid grid-cols-1 md:grid-cols-4 gap-4 mb-6">
    <!-- Total Reservasi -->
    <div class="bg-gradient-to-br from-amber-400 to-yellow-500 text-white rounded-lg shadow-lg p-4">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-amber-50 text-sm font-medium">Total Reservasi</p>
                <p class="text-2xl font-bold mt-1">{{ $stats['total_reservations'] }}</p>
            </div>
            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M8 7V3m8 4V3m-9 8h10M5 21h14a2 2 0 002-2V7a2 2 0 00-2-2H5a2 2 0 00-2 2v12a2 2 0 002 2z" />
                </svg>
            </div>
        </div>
        <div class="mt-2">
            <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded-full font-medium">
                {{ $stats['today_reservations'] }} hari ini
            </span>
        </div>
    </div>

    <!-- Pendapatan Hari Ini -->
    <div class="bg-gradient-to-br from-yellow-600 to-amber-700 text-white rounded-lg shadow-lg p-4">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-yellow-50 text-sm font-medium">Pendapatan Hari Ini</p>
                <p class="text-2xl font-bold mt-1">Rp{{ number_format($stats['today_revenue'], 0, ',', '.') }}</p>
            </div>
            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                </svg>
            </div>
        </div>
        <div class="mt-2">
            <span class="text-xs bg-white bg-opacity-20 px-2 py-1 rounded-full font-medium">
                {{ $stats['today_sukses'] }} transaksi sukses
            </span>
        </div>
    </div>

    <!-- Ruang Aktif -->
    <div class="bg-gradient-to-br from-amber-600 to-orange-600 text-white rounded-lg shadow-lg p-4">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-amber-50 text-sm font-medium">Ruang Aktif</p>
                <p class="text-2xl font-bold mt-1">{{ $stats['active_rooms'] }}</p>
            </div>
            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                </svg>
            </div>
        </div>
    </div>

    <!-- Promo Aktif -->
    <div class="bg-gradient-to-br from-orange-500 to-red-600 text-white rounded-lg shadow-lg p-4">
        <div class="flex justify-between items-start">
            <div>
                <p class="text-orange-50 text-sm font-medium">Promo Aktif</p>
                <p class="text-2xl font-bold mt-1">{{ $stats['active_promotions'] }}</p>
            </div>
            <div class="bg-white bg-opacity-20 p-2 rounded-lg">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                </svg>
            </div>
        </div>
    </div>
</div>

<!-- Reservasi Terbaru & Akses Cepat -->
<div class="grid grid-cols-1 lg:grid-cols-2 gap-6">
    <!-- Reservasi Terbaru -->
    <div class="bg-white rounded-xl shadow p-6">
        <div class="flex justify-between items-center mb-4">
            <h2 class="text-xl font-bold">Reservasi Terbaru</h2>
            <a href="{{ route('admin.reservations') }}" 
               class="inline-flex items-center gap-2 bg-gradient-to-r from-amber-500 to-yellow-500 hover:from-amber-600 hover:to-yellow-600 text-white text-sm font-semibold px-4 py-2 rounded-lg shadow-md shadow-amber-200 hover:shadow-lg hover:shadow-amber-300 transition-all duration-200 transform hover:-translate-y-0.5">
                <i class="fas fa-list-ul text-xs"></i>
                Lihat Semua
                <i class="fas fa-arrow-right text-xs"></i>
            </a>
        </div>

        @if($recentReservations->count() > 0)
            <div class="space-y-4">
                @foreach($recentReservations as $res)
                    <div class="border border-gray-200 rounded-lg p-4 hover:shadow-md transition hover:border-amber-300">
                        <div class="flex justify-between">
                            <div>
                                <h3 class="font-semibold text-gray-800">#{{ str_pad($res->id, 6, '0', STR_PAD_LEFT) }}</h3>
                                <p class="text-sm text-gray-600 mt-1">{{ $res->meetingRoom->name }}</p>
                            </div>
                            <div class="text-right">
                                @php
                                    $discountPercent = $res->promotion?->discount ?? 0;
                                    $discountAmount = ($res->total_price * $discountPercent) / 100;
                                @endphp
                                <p class="font-medium">
                                    Rp{{ number_format($res->total_price - $discountAmount, 0, ',', '.') }}
                                </p>
                                <p class="text-sm text-gray-500">
                                    {{ \Carbon\Carbon::parse($res->date)->locale('id')->isoFormat('D MMM') }}
                                </p>
                            </div>
                        </div>
                        <div class="mt-3 flex items-center justify-between">
                            <div>
                                @if($res->status == 'sukses')
                                    <span class="bg-green-100 text-green-800 text-xs px-2 py-1 rounded-full">Sukses</span>
                                @elseif($res->status == 'pending')
                                    <span class="bg-yellow-100 text-yellow-800 text-xs px-2 py-1 rounded-full">Pending</span>
                                @else
                                    <span class="bg-red-100 text-red-800 text-xs px-2 py-1 rounded-full">Dibatalkan</span>
                                @endif
                            </div>
                            <a href="{{ route('admin.reservation.show', $res->id) }}" 
                               class="text-amber-600 hover:text-amber-800 text-sm font-medium flex items-center gap-1">
                                <span>Lihat Detail</span>
                                <i class="fas fa-arrow-right text-xs"></i>
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="text-center py-8 text-gray-500">
                <svg xmlns="http://www.w3.org/2000/svg" class="h-12 w-12 mx-auto text-gray-300" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z" />
                </svg>
                <p class="mt-2">Belum ada reservasi</p>
            </div>
        @endif
    </div>

    <!-- Akses Cepat -->
    <div class="bg-white rounded-xl shadow p-6">
        <h2 class="text-xl font-bold mb-4">Akses Cepat</h2>
        <div class="grid grid-cols-2 gap-4">
            <a href="{{ route('admin.rooms.index') }}"
               class="block p-4 bg-gradient-to-br from-amber-50 to-yellow-50 hover:from-amber-100 hover:to-yellow-100 rounded-lg text-center transition group border border-amber-100">
                <div class="bg-gradient-to-br from-amber-400 to-yellow-500 w-12 h-12 rounded-lg flex items-center justify-center mx-auto group-hover:scale-105 transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 21V5a2 2 0 00-2-2H7a2 2 0 00-2 2v16m14 0h2m-2 0h-5m-9 0H3m2 0h5M9 7h1m-1 4h1m4-4h1m-1 4h1m-5 10v-5a1 1 0 011-1h2a1 1 0 011 1v5m-4 0h4" />
                    </svg>
                </div>
                <div class="mt-3 font-medium text-gray-800">Ruang Meeting</div>
            </a>

            <a href="{{ route('admin.packages.index') }}"
               class="block p-4 bg-gradient-to-br from-yellow-50 to-amber-50 hover:from-yellow-100 hover:to-amber-100 rounded-lg text-center transition group border border-yellow-100">
                <div class="bg-gradient-to-br from-yellow-600 to-amber-700 w-12 h-12 rounded-lg flex items-center justify-center mx-auto group-hover:scale-105 transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 8c-1.657 0-3 .895-3 2s1.343 2 3 2 3 .895 3 2-1.343 2-3 2m0-8c1.11 0 2.08.402 2.599 1M12 8V7m0 1v8m0 0v1m0-1c-1.11 0-2.08-.402-2.599-1M21 12a9 9 0 11-18 0 9 9 0 0118 0z" />
                    </svg>
                </div>
                <div class="mt-3 font-medium text-gray-800">Paket Makanan</div>
            </a>

            <a href="{{ route('admin.promotions.index') }}"
               class="block p-4 bg-gradient-to-br from-amber-50 to-orange-50 hover:from-amber-100 hover:to-orange-100 rounded-lg text-center transition group border border-amber-100">
                <div class="bg-gradient-to-br from-amber-600 to-orange-600 w-12 h-12 rounded-lg flex items-center justify-center mx-auto group-hover:scale-105 transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11.049 2.927c.3-.921 1.603-.921 1.902 0l1.519 4.674a1 1 0 00.95.69h4.915c.969 0 1.371 1.24.588 1.81l-3.976 2.888a1 1 0 00-.363 1.118l1.518 4.674c.3.922-.755 1.688-1.538 1.118l-3.976-2.888a1 1 0 00-1.176 0l-3.976 2.888c-.783.57-1.838-.197-1.538-1.118l1.518-4.674a1 1 0 00-.363-1.118l-3.976-2.888c-.784-.57-.38-1.81.588-1.81h4.914a1 1 0 00.951-.69l1.519-4.674z" />
                    </svg>
                </div>
                <div class="mt-3 font-medium text-gray-800">Promo</div>
            </a>

            <a href="{{ route('admin.reports') }}"
               class="block p-4 bg-gradient-to-br from-orange-50 to-red-50 hover:from-orange-100 hover:to-red-100 rounded-lg text-center transition group border border-orange-100">
                <div class="bg-gradient-to-br from-orange-500 to-red-600 w-12 h-12 rounded-lg flex items-center justify-center mx-auto group-hover:scale-105 transition shadow-md">
                    <svg xmlns="http://www.w3.org/2000/svg" class="h-6 w-6 text-white" fill="none" viewBox="0 0 24 24" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 19v-6a2 2 0 00-2-2H5a2 2 0 00-2 2v6a2 2 0 002 2h2a2 2 0 002-2zm0 0V9a2 2 0 012-2h2a2 2 0 012 2v10m-6 0a2 2 0 002 2h2a2 2 0 002-2m0 0V5a2 2 0 012-2h2a2 2 0 012 2v14a2 2 0 01-2 2h-2a2 2 0 01-2-2z" />
                    </svg>
                </div>
                <div class="mt-3 font-medium text-gray-800">Laporan</div>
            </a>
        </div>
    </div>
</div>
@endsection
