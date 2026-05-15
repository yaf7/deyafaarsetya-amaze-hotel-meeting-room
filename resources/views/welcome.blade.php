@extends('admin.layouts.app')

@section('page-title', 'Dashboard')

@section('content')
<!-- Welcome Section -->
<div class="mb-4 sm:mb-6">
    <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Dashboard</h1>
    <p class="text-gray-500 text-sm">Selamat datang di Admin Panel</p>
</div>

<!-- Stats Cards -->
<div class="grid grid-cols-2 lg:grid-cols-4 gap-3 sm:gap-4 lg:gap-6 mb-6 sm:mb-8">
    <!-- Total Reservasi -->
    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Total Reservasi</p>
                <p class="text-xl sm:text-3xl font-bold text-gray-800">{{ $stats['total_reservations'] }}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-amber-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                <i class="fas fa-calendar-check text-amber-700 text-sm sm:text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Pendapatan Hari Ini -->
    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Hari Ini</p>
                <p class="text-lg sm:text-2xl font-bold text-gray-800">Rp{{ number_format($stats['today_revenue']/1000, 0) }}K</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-stone-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                <i class="fas fa-wallet text-stone-700 text-sm sm:text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Ruang Aktif -->
    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Ruang</p>
                <p class="text-xl sm:text-3xl font-bold text-gray-800">{{ $stats['active_rooms'] }}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-yellow-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                <i class="fas fa-door-open text-yellow-700 text-sm sm:text-xl"></i>
            </div>
        </div>
    </div>

    <!-- Promo Aktif -->
    <div class="bg-white rounded-xl sm:rounded-2xl p-4 sm:p-6 shadow-sm border border-gray-100">
        <div class="flex items-center justify-between">
            <div>
                <p class="text-xs sm:text-sm text-gray-500 mb-1">Promo</p>
                <p class="text-xl sm:text-3xl font-bold text-gray-800">{{ $stats['active_promotions'] }}</p>
            </div>
            <div class="w-10 h-10 sm:w-12 sm:h-12 bg-orange-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                <i class="fas fa-tags text-orange-700 text-sm sm:text-xl"></i>
            </div>
        </div>
    </div>
</div>

<!-- Main Content Grid -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-4 sm:gap-6">
    <!-- Reservasi Terbaru -->
    <div class="lg:col-span-2 bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="flex items-center justify-between p-4 sm:p-6 border-b border-gray-100">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-amber-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                    <i class="fas fa-list text-amber-700 text-sm sm:text-base"></i>
                </div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-800">Reservasi Terbaru</h2>
            </div>
            <a href="{{ route('admin.reports') }}" class="text-xs sm:text-sm text-amber-700 hover:text-amber-800 font-medium">
                Semua <i class="fas fa-arrow-right ml-1"></i>
            </a>
        </div>

        @if($recentReservations->count() > 0)
            <div class="divide-y divide-gray-100 max-h-72 sm:max-h-80 overflow-y-auto">
                @foreach($recentReservations as $res)
                    <div class="p-3 sm:p-4 hover:bg-gray-50 transition-colors">
                        <div class="flex items-center justify-between">
                            <div class="flex items-center gap-3 sm:gap-4">
                                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-gray-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                                    <span class="text-xs font-bold text-gray-600">#{{ $res->id }}</span>
                                </div>
                                <div>
                                    <p class="font-medium text-gray-800 text-sm sm:text-base">{{ $res->meetingRoom->name }}</p>
                                    <p class="text-xs sm:text-sm text-gray-500">{{ \Carbon\Carbon::parse($res->date)->format('d M Y') }}</p>
                                </div>
                            </div>
                            <div class="text-right">
                                @php
                                    $discountPercent = $res->promotion?->discount ?? 0;
                                    $discountAmount = ($res->total_price * $discountPercent) / 100;
                                @endphp
                                <p class="font-semibold text-gray-800 text-xs sm:text-sm">Rp{{ number_format($res->total_price - $discountAmount, 0, ',', '.') }}</p>
                                @if($res->status == 'sukses')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-green-100 text-green-700">Sukses</span>
                                @elseif($res->status == 'pending')
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-yellow-100 text-yellow-700">Pending</span>
                                @else
                                    <span class="inline-flex items-center px-2 py-0.5 rounded-full text-[10px] sm:text-xs font-medium bg-red-100 text-red-700">Batal</span>
                                @endif
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @else
            <div class="p-8 sm:p-12 text-center">
                <div class="w-12 h-12 sm:w-16 sm:h-16 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <i class="fas fa-inbox text-gray-400 text-xl sm:text-2xl"></i>
                </div>
                <p class="text-gray-500 text-sm">Belum ada reservasi</p>
            </div>
        @endif
    </div>

    <!-- Quick Actions -->
    <div class="bg-white rounded-xl sm:rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="p-4 sm:p-6 border-b border-gray-100">
            <div class="flex items-center gap-2 sm:gap-3">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-stone-100 rounded-lg sm:rounded-xl flex items-center justify-center">
                    <i class="fas fa-bolt text-stone-700 text-sm sm:text-base"></i>
                </div>
                <h2 class="text-base sm:text-lg font-semibold text-gray-800">Akses Cepat</h2>
            </div>
        </div>
        
        <div class="p-3 sm:p-4 space-y-2 sm:space-y-3">
            <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-lg sm:rounded-xl bg-amber-50 hover:bg-amber-100 transition-colors group">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-amber-600 rounded-lg sm:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-door-open text-white text-sm sm:text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-800 text-sm sm:text-base">Ruang Meeting</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
            </a>

            <a href="{{ route('admin.packages.index') }}" class="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-lg sm:rounded-xl bg-stone-50 hover:bg-stone-100 transition-colors group">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-stone-600 rounded-lg sm:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-utensils text-white text-sm sm:text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-800 text-sm sm:text-base">Paket Makanan</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
            </a>

            <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-lg sm:rounded-xl bg-yellow-50 hover:bg-yellow-100 transition-colors group">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-yellow-600 rounded-lg sm:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-tags text-white text-sm sm:text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-800 text-sm sm:text-base">Promo</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
            </a>

            <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 sm:gap-4 p-3 sm:p-4 rounded-lg sm:rounded-xl bg-orange-50 hover:bg-orange-100 transition-colors group">
                <div class="w-8 h-8 sm:w-10 sm:h-10 bg-orange-600 rounded-lg sm:rounded-xl flex items-center justify-center group-hover:scale-110 transition-transform">
                    <i class="fas fa-chart-bar text-white text-sm sm:text-base"></i>
                </div>
                <div class="flex-1 min-w-0">
                    <p class="font-medium text-gray-800 text-sm sm:text-base">Laporan</p>
                </div>
                <i class="fas fa-chevron-right text-gray-400 text-sm"></i>
            </a>
        </div>
    </div>
</div>
@endsection
