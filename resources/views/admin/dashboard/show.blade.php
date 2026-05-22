@extends('admin.layouts.app')

@section('page-title', 'Detail Reservasi')

@section('content')
<!-- Header -->
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.dashboard') }}" 
           class="w-9 h-9 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div class="flex-1">
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Detail Reservasi #{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</h1>
            <p class="text-gray-500 text-sm">Informasi lengkap reservasi customer</p>
        </div>
        @if($reservation->status == 'sukses')
            <span class="bg-green-100 text-green-700 px-3 py-1.5 rounded-lg text-sm font-semibold">Sukses</span>
        @elseif($reservation->status == 'pending')
            <span class="bg-yellow-100 text-yellow-700 px-3 py-1.5 rounded-lg text-sm font-semibold">Pending</span>
        @else
            <span class="bg-red-100 text-red-700 px-3 py-1.5 rounded-lg text-sm font-semibold">Dibatalkan</span>
        @endif
    </div>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 text-sm">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Grid Layout -->
<div class="grid grid-cols-1 lg:grid-cols-3 gap-6">
    <!-- Main Content (2 columns) -->
    <div class="lg:col-span-2 space-y-6">
        <!-- Banner Reschedule (Jika Ada) -->
        @if($reservation->reschedule_status === 'pending')
        <div class="bg-indigo-50 border border-indigo-200 rounded-2xl shadow-sm p-6 relative overflow-hidden">
            <div class="absolute top-0 right-0 -mt-4 -mr-4 w-24 h-24 bg-indigo-500 rounded-full opacity-10 blur-2xl"></div>
            
            <div class="flex items-start gap-4 relative z-10">
                <div class="w-12 h-12 bg-white rounded-xl shadow-sm flex items-center justify-center flex-shrink-0">
                    <i class="fas fa-calendar-alt text-indigo-600 text-xl animate-pulse"></i>
                </div>
                <div class="flex-1">
                    <h2 class="text-lg font-bold text-indigo-900 mb-1">Pengajuan Reschedule Jadwal</h2>
                    <p class="text-indigo-700 text-sm mb-4">Customer mengajukan perubahan jadwal. Harap tinjau jadwal baru di bawah ini dan pastikan tidak bentrok dengan pesanan lain.</p>
                    
                    <div class="bg-white rounded-xl border border-indigo-100 p-4 mb-4 grid grid-cols-1 md:grid-cols-2 gap-4">
                        <div>
                            <p class="text-xs text-gray-500 font-medium mb-1">Jadwal Lama</p>
                            <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($reservation->date)->locale('id')->isoFormat('D MMMM Y') }}</p>
                            <p class="text-sm text-gray-600">{{ $reservation->time }}</p>
                        </div>
                        <div class="md:border-l border-indigo-50 md:pl-4">
                            <p class="text-xs text-indigo-500 font-bold mb-1">Jadwal Baru (Usulan)</p>
                            <p class="font-bold text-indigo-900">{{ \Carbon\Carbon::parse($reservation->requested_reschedule_date)->locale('id')->isoFormat('D MMMM Y') }}</p>
                            <p class="text-sm font-semibold text-indigo-700">{{ $reservation->requested_reschedule_session }}</p>
                        </div>
                    </div>

                    <div class="flex flex-wrap gap-3">
                        <form action="{{ route('admin.reservation.reschedule.approve', $reservation->id) }}" method="POST" class="inline">
                            @csrf
                            <button type="submit" onclick="return confirm('Apakah Anda yakin menyetujui perubahan jadwal ini? Jadwal lama akan dihapus dan diganti dengan jadwal baru.')" class="bg-indigo-600 hover:bg-indigo-700 text-white px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-md hover:shadow-lg flex items-center gap-2">
                                <i class="fas fa-check"></i> Setujui Reschedule
                            </button>
                        </form>
                        <button type="button" onclick="document.getElementById('reject-reschedule-modal').classList.remove('hidden'); document.getElementById('reject-reschedule-modal').classList.add('flex'); setTimeout(() => { document.getElementById('reject-reschedule-modal-card').classList.remove('scale-95'); document.getElementById('reject-reschedule-modal-card').classList.add('scale-100'); }, 10);" class="bg-white hover:bg-gray-50 border border-gray-200 text-gray-700 px-5 py-2.5 rounded-xl font-semibold text-sm transition shadow-sm flex items-center gap-2">
                            <i class="fas fa-times"></i> Tolak
                        </button>
                    </div>
                </div>
            </div>
        </div>
        @elseif($reservation->reschedule_status === 'approved')
        <div class="bg-sky-50 border border-sky-200 rounded-2xl shadow-sm p-4 flex items-center gap-4">
            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-sky-600 flex-shrink-0">
                <i class="fas fa-check-circle"></i>
            </div>
            <div>
                <p class="font-bold text-sky-900 text-sm">Reschedule Berhasil Disetujui</p>
                <p class="text-sky-700 text-xs">Jadwal telah diubah ke {{ \Carbon\Carbon::parse($reservation->date)->locale('id')->isoFormat('D MMMM Y') }} ({{ $reservation->time }})</p>
            </div>
        </div>
        @elseif($reservation->reschedule_status === 'rejected')
        <div class="bg-rose-50 border border-rose-200 rounded-2xl shadow-sm p-4 flex items-start gap-4">
            <div class="w-10 h-10 bg-white rounded-xl shadow-sm flex items-center justify-center text-rose-600 flex-shrink-0">
                <i class="fas fa-times-circle text-lg"></i>
            </div>
            <div>
                <p class="font-bold text-rose-900 text-sm mb-1">Reschedule Ditolak</p>
                <p class="text-rose-700 text-sm bg-rose-100/50 p-2.5 rounded-lg border border-rose-100">"{{ $reservation->reschedule_rejection_reason }}"</p>
            </div>
        </div>
        @endif

        <!-- Data Pemesan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-user text-amber-600"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Data Pemesan</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Nama Pemesan</p>
                    <p class="font-semibold text-gray-800">
                        {{ $reservation->customer_name }}
                        @if($reservation->customer && $reservation->customer->company_name)
                            <span class="text-sm font-normal text-gray-500">({{ $reservation->customer->company_name }})</span>
                        @endif
                    </p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Nomor Telepon</p>
                    <p class="font-semibold text-gray-800">{{ $reservation->phone }}</p>
                </div>
            </div>
        </div>

        <!-- Ruangan & Jadwal -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-door-open text-amber-600"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Ruangan & Jadwal</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Ruangan</p>
                    <p class="font-semibold text-gray-800">{{ $reservation->meetingRoom->name }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Layout</p>
                    <p class="font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $reservation->layout ?? 'Theater')) }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Tanggal</p>
                    <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($reservation->date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Sesi</p>
                    <p class="font-semibold text-gray-800">{{ $reservation->time }}</p>
                </div>
            </div>
        </div>

        <!-- Peserta & Paket -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-users text-amber-600"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Peserta & Paket Makanan</h2>
            </div>
            <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Jumlah Peserta</p>
                    <p class="font-semibold text-gray-800">{{ $reservation->participants }} orang</p>
                </div>
                <div class="bg-gray-50 rounded-xl p-4">
                    <p class="text-xs text-gray-500 mb-1">Paket Makanan</p>
                    <p class="font-semibold text-gray-800">{{ $reservation->foodPackage->name }}</p>
                </div>
                @if($reservation->residential_type)
                    <div class="bg-purple-50 rounded-xl p-4 border border-purple-200">
                        <p class="text-xs text-purple-700 mb-1">Tipe Kamar Menginap</p>
                        <p class="font-semibold text-purple-800 flex items-center gap-2">
                            <i class="fas fa-bed text-xs"></i>
                            {{ $reservation->residential_type === 'twin' ? 'Twin Sharing (1 kamar untuk 2 orang)' : 'Single Occupancy (1 kamar untuk 1 orang)' }}
                        </p>
                    </div>
                @endif
                @if($reservation->promotion)
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-200">
                        <p class="text-xs text-amber-700 mb-1">Promo Aktif</p>
                        <p class="font-semibold text-amber-800 flex items-center gap-2">
                            <i class="fas fa-tag text-xs"></i>
                            {{ $reservation->promotion->name }}
                        </p>
                    </div>
                @endif
            </div>

            {{-- Rincian Harga untuk Paket Residential --}}
            @php
                $pkgName = strtolower($reservation->foodPackage->name ?? '');
                $isResidential = str_contains($pkgName, 'residential');
                $resBaseMap = [
                    'residential full day meeting' => ['base' => 235000, 'twin' => 315000, 'single' => 415000],
                    'residential full board meeting' => ['base' => 380000, 'twin' => 220000, 'single' => 320000],
                ];
                $resInfo = $resBaseMap[$pkgName] ?? null;
            @endphp
            @if($isResidential && $resInfo)
                <div class="mt-4 bg-purple-50 rounded-xl p-4 border border-purple-100">
                    <p class="text-xs font-bold text-purple-700 uppercase tracking-wider mb-3">
                        <i class="fas fa-calculator mr-1"></i> Rincian Harga Residential
                    </p>
                    <div class="space-y-2 text-sm">
                        <div class="flex justify-between">
                            <span class="text-gray-600">Harga Meeting /pax</span>
                            <span class="font-semibold text-gray-800">Rp{{ number_format($resInfo['base'], 0, ',', '.') }} × {{ $reservation->participants }} orang</span>
                        </div>
                        <div class="flex justify-between">
                            <span class="text-gray-600">Biaya Kamar Transit ({{ $reservation->residential_type === 'single' ? 'Single' : 'Twin' }})</span>
                            <span class="font-semibold text-gray-800">+ Rp{{ number_format($resInfo[$reservation->residential_type ?? 'twin'], 0, ',', '.') }}</span>
                        </div>
                        <div class="flex justify-between pt-2 border-t border-purple-200">
                            <span class="font-bold text-gray-800">Subtotal</span>
                            <span class="font-bold text-amber-600">Rp{{ number_format($reservation->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>
            @endif
        </div>

        <!-- Fasilitas Ruangan -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-blue-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-building text-blue-600"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Fasilitas Ruangan</h2>
            </div>
            <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-tv text-blue-500 text-xs w-4 text-center"></i> LCD Projector + Screen
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-volume-up text-blue-500 text-xs w-4 text-center"></i> Sound System
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-chalkboard text-blue-500 text-xs w-4 text-center"></i> Flipchart & Writing Materials
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-tint text-blue-500 text-xs w-4 text-center"></i> Air Mineral
                    </li>
                </ul>
            </div>
        </div>

        <!-- Menu Include -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-gift text-amber-600"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Menu Include</h2>
            </div>
            <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                <ul class="space-y-2 text-sm text-gray-700">
                    <li class="flex items-center gap-2">
                        <i class="fas fa-bowl-rice text-amber-500 text-xs w-4 text-center"></i> Nasi Putih
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-apple-whole text-amber-500 text-xs w-4 text-center"></i> 2 Kind of Slice Fruit
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-cake-candles text-amber-500 text-xs w-4 text-center"></i> Assorted Dessert
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-glass-water text-amber-500 text-xs w-4 text-center"></i> Any Kind Juice
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-bottle-water text-amber-500 text-xs w-4 text-center"></i> Mineral Dispenser
                    </li>
                    <li class="flex items-center gap-2">
                        <i class="fas fa-mug-hot text-amber-500 text-xs w-4 text-center"></i> Coffee Break
                    </li>
                </ul>
                <p class="text-xs text-gray-500 mt-2 italic">Terdiri dari kopi, teh, dan 2 jenis snack pilihan chef</p>
            </div>
        </div>

        <!-- Menu Buffet yang Dipilih -->
        @if($reservation->buffetSelections->count() > 0)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
                <div class="flex items-center gap-3 mb-4">
                    <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                        <i class="fas fa-utensils text-amber-600"></i>
                    </div>
                    <h2 class="text-lg font-bold text-gray-800">Menu Buffet yang Dipilih</h2>
                    <span class="ml-auto bg-amber-100 text-amber-700 px-3 py-1 rounded-full text-sm font-semibold">
                        {{ $reservation->buffetSelections->count() }} menu
                    </span>
                </div>
                
                @php
                    $groupedMenus = $reservation->buffetSelections->groupBy(function($selection) {
                        return $selection->buffetMenu->category;
                    });
                    $categoryOrder = ['soup', 'mie', 'ayam', 'ikan', 'sayuran', 'fritter'];
                    $categoryLabels = [
                        'soup' => 'Soup',
                        'mie' => 'Mie',
                        'ayam' => 'Ayam',
                        'ikan' => 'Ikan',
                        'sayuran' => 'Sayuran',
                        'fritter' => 'Fritter'
                    ];
                    $categoryColors = [
                        'soup' => 'bg-orange-50 text-orange-700 border-orange-200',
                        'mie' => 'bg-yellow-50 text-yellow-700 border-yellow-200',
                        'ayam' => 'bg-red-50 text-red-700 border-red-200',
                        'ikan' => 'bg-blue-50 text-blue-700 border-blue-200',
                        'sayuran' => 'bg-green-50 text-green-700 border-green-200',
                        'fritter' => 'bg-amber-50 text-amber-700 border-amber-200'
                    ];
                @endphp

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    @foreach($categoryOrder as $category)
                        @if(isset($groupedMenus[$category]))
                            <div class="border rounded-xl p-4 {{ $categoryColors[$category] ?? 'bg-gray-50' }}">
                                <div class="flex items-center justify-between mb-3">
                                    <h3 class="font-bold text-sm uppercase tracking-wide">{{ $categoryLabels[$category] }}</h3>
                                    <span class="text-xs font-bold px-2.5 py-1 rounded-full {{ str_replace('50', '100', $categoryColors[$category] ?? 'bg-gray-200') }}">
                                        {{ $groupedMenus[$category]->count() }}
                                    </span>
                                </div>
                                <ul class="space-y-2 text-sm">
                                    @foreach($groupedMenus[$category] as $selection)
                                        <li class="flex items-start gap-2">
                                            <i class="fas fa-check-circle text-xs mt-1 opacity-70"></i>
                                            <span class="flex-1">{{ $selection->buffetMenu->name }}</span>
                                        </li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif
                    @endforeach
                </div>
            </div>
        @endif
    </div>

    <!-- Sidebar (1 column) -->
    <div class="lg:col-span-1 space-y-6">
        <!-- Rincian Pembayaran -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-receipt text-amber-600"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Pembayaran</h2>
            </div>
            <div class="space-y-3">
                <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                    <span class="text-gray-600 text-sm">Subtotal</span>
                    <span class="font-semibold text-gray-800">Rp{{ number_format($reservation->total_price, 0, ',', '.') }}</span>
                </div>
                
                @if($reservation->promotion)
                    @php
                        $discountPercent = $reservation->promotion->discount;
                        $discountAmount = ($reservation->total_price * $discountPercent) / 100;
                    @endphp
                    <div class="flex justify-between items-center pb-3 border-b border-gray-100">
                        <span class="text-green-600 text-sm">Diskon ({{ $discountPercent }}%)</span>
                        <span class="font-semibold text-green-600">-Rp{{ number_format($discountAmount, 0, ',', '.') }}</span>
                    </div>
                @endif
                
                @php
                    $discountPercent = $reservation->promotion?->discount ?? 0;
                    $discountAmount = ($reservation->total_price * $discountPercent) / 100;
                    $finalPrice = $reservation->total_price - $discountAmount;
                @endphp
                
                <div class="flex justify-between items-center pt-2">
                    <span class="text-gray-800 font-bold">Total Bayar</span>
                    <span class="text-xl font-bold text-amber-600">Rp{{ number_format($finalPrice, 0, ',', '.') }}</span>
                </div>

                <div class="pt-3 border-t border-gray-100">
                    <p class="text-xs text-gray-500 mb-1">Status Pembayaran</p>
                    @if($reservation->payment && $reservation->payment->payment_status == 'sukses')
                        <div class="flex items-center gap-2 text-green-700 bg-green-50 px-3 py-2 rounded-lg">
                            <i class="fas fa-check-circle"></i>
                            <span class="font-semibold text-sm">Sudah Dibayar</span>
                        </div>
                    @elseif($reservation->payment && $reservation->payment->payment_status == 'gagal')
                        <div class="flex items-center gap-2 text-red-700 bg-red-50 px-3 py-2 rounded-lg">
                            <i class="fas fa-times-circle"></i>
                            <span class="font-semibold text-sm">Gagal</span>
                        </div>
                    @else
                        <div class="flex items-center gap-2 text-yellow-700 bg-yellow-50 px-3 py-2 rounded-lg">
                            <i class="fas fa-clock"></i>
                            <span class="font-semibold text-sm">Menunggu Pembayaran</span>
                        </div>
                    @endif
                </div>

                {{-- Detail Pembayaran Midtrans --}}
                @if($reservation->payment)
                    @if($reservation->payment->transaction_id)
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-1">Transaction ID</p>
                            <p class="font-mono text-sm text-gray-800 break-all">{{ $reservation->payment->transaction_id }}</p>
                        </div>
                    @endif

                    @if($reservation->payment->payment_method)
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-1">Metode Pembayaran</p>
                            <p class="font-semibold text-sm text-gray-800">
                                @switch($reservation->payment->payment_method)
                                    @case('bank_transfer')
                                        <i class="fas fa-university text-blue-500 mr-1"></i> Bank Transfer
                                        @break
                                    @case('qris')
                                        <i class="fas fa-qrcode text-purple-500 mr-1"></i> QRIS
                                        @break
                                    @case('gopay')
                                        <i class="fas fa-wallet text-green-500 mr-1"></i> GoPay
                                        @break
                                    @case('shopeepay')
                                        <i class="fas fa-wallet text-orange-500 mr-1"></i> ShopeePay
                                        @break
                                    @case('credit_card')
                                        <i class="fas fa-credit-card text-amber-500 mr-1"></i> Kartu Kredit
                                        @break
                                    @case('simulasi')
                                        <i class="fas fa-flask text-gray-500 mr-1"></i> Simulasi
                                        @break
                                    @default
                                        <i class="fas fa-money-bill text-gray-500 mr-1"></i> {{ ucfirst(str_replace('_', ' ', $reservation->payment->payment_method)) }}
                                @endswitch
                            </p>
                        </div>
                    @endif

                    @if($reservation->payment->gross_amount)
                        <div class="pt-3 border-t border-gray-100">
                            <p class="text-xs text-gray-500 mb-1">Jumlah Dibayar</p>
                            <p class="font-bold text-amber-600">Rp{{ number_format($reservation->payment->gross_amount, 0, ',', '.') }}</p>
                        </div>
                    @endif
                @endif
            </div>
        </div>

        <!-- Simulasi Notifikasi WhatsApp Otomatis -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 {{ $reservation->whatsapp_sent ? 'bg-green-100' : 'bg-gray-100' }} rounded-xl flex items-center justify-center">
                    <i class="fab fa-whatsapp {{ $reservation->whatsapp_sent ? 'text-green-600' : 'text-gray-400' }} text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Notifikasi WhatsApp</h2>
                    <p class="text-xs text-gray-500">Simulasi otomatis</p>
                </div>
            </div>
            
            @if($reservation->whatsapp_sent)
                <div class="bg-green-50 border border-green-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-2 text-green-700 mb-1">
                        <i class="fas fa-check-circle text-sm"></i>
                        <span class="font-semibold text-sm">Notifikasi Terkirim</span>
                    </div>
                    <p class="text-xs text-green-600">
                        Terkirim otomatis pada {{ $reservation->whatsapp_sent_at ? $reservation->whatsapp_sent_at->locale('id')->isoFormat('D MMM Y, HH:mm') : '-' }}
                    </p>
                </div>
                <a href="{{ route('admin.reservation.whatsapp', $reservation->id) }}"
                   class="w-full bg-white border-2 border-green-500 hover:bg-green-50 text-green-600 px-4 py-3 rounded-xl font-semibold transition-colors flex items-center justify-center gap-2">
                    <i class="fab fa-whatsapp text-lg"></i>
                    Lihat Notifikasi
                </a>
            @else
                <div class="bg-amber-50 border border-amber-200 rounded-xl p-4 mb-4">
                    <div class="flex items-center gap-2 text-amber-700 mb-1">
                        <i class="fas fa-clock text-sm"></i>
                        <span class="font-semibold text-sm">Menunggu Konfirmasi</span>
                    </div>
                    <p class="text-xs text-amber-600">Notifikasi WhatsApp akan otomatis terkirim saat status diubah ke <strong>Sukses</strong>.</p>
                </div>
                <a href="{{ route('admin.reservation.whatsapp', $reservation->id) }}"
                   class="w-full bg-gray-100 hover:bg-gray-200 text-gray-500 px-4 py-3 rounded-xl font-semibold transition-colors flex items-center justify-center gap-2">
                    <i class="fab fa-whatsapp text-lg"></i>
                    Lihat Halaman Notifikasi
                </a>
            @endif
        </div>

        <!-- Update Status -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 bg-amber-100 rounded-xl flex items-center justify-center">
                    <i class="fas fa-edit text-amber-600"></i>
                </div>
                <h2 class="text-lg font-bold text-gray-800">Update Status</h2>
            </div>
            <form method="POST" action="{{ route('admin.reservation.update-status', $reservation->id) }}">
                @csrf
                <div class="space-y-4">
                    <div>
                        <label class="block text-sm font-semibold text-gray-700 mb-2">Status Reservasi</label>
                        <select name="status" class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                            <option value="pending" {{ $reservation->status == 'pending' ? 'selected' : '' }}>Pending</option>
                            <option value="sukses" {{ $reservation->status == 'sukses' ? 'selected' : '' }}>Sukses</option>
                            <option value="dibatalkan" {{ $reservation->status == 'dibatalkan' ? 'selected' : '' }}>Dibatalkan</option>
                        </select>
                    </div>
                    <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white px-4 py-3 rounded-xl font-semibold transition-colors">
                        <i class="fas fa-save mr-2"></i>
                        Simpan Perubahan
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Reject Reschedule Modal -->
@if($reservation->reschedule_status === 'pending')
<div id="reject-reschedule-modal" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
    <div class="bg-white rounded-3xl max-w-md w-full shadow-2xl overflow-hidden border border-gray-100 transform scale-95 transition-transform duration-300" id="reject-reschedule-modal-card">
        <div class="bg-rose-50 px-6 py-5 border-b border-rose-100 relative">
            <h3 class="text-xl font-bold text-rose-700">Tolak Reschedule</h3>
            <button type="button" onclick="document.getElementById('reject-reschedule-modal-card').classList.remove('scale-100'); document.getElementById('reject-reschedule-modal-card').classList.add('scale-95'); setTimeout(() => { document.getElementById('reject-reschedule-modal').classList.remove('flex'); document.getElementById('reject-reschedule-modal').classList.add('hidden'); }, 150);" class="absolute top-5 right-5 text-gray-400 hover:text-gray-600 transition-colors">
                <i class="fas fa-times text-lg"></i>
            </button>
        </div>

        <form action="{{ route('admin.reservation.reschedule.reject', $reservation->id) }}" method="POST" class="p-6">
            @csrf
            <div class="mb-5">
                <label for="rejection_reason" class="block text-sm font-bold text-gray-800 mb-2">Alasan Penolakan <span class="text-red-500">*</span></label>
                <textarea name="rejection_reason" id="rejection_reason" rows="3" required
                          placeholder="Beri tahu pelanggan mengapa reschedule ditolak (misal: Ruangan penuh untuk sesi tersebut)..."
                          class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-rose-500 focus:border-transparent transition-all"></textarea>
            </div>

            <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                <button type="button" onclick="document.getElementById('reject-reschedule-modal-card').classList.remove('scale-100'); document.getElementById('reject-reschedule-modal-card').classList.add('scale-95'); setTimeout(() => { document.getElementById('reject-reschedule-modal').classList.remove('flex'); document.getElementById('reject-reschedule-modal').classList.add('hidden'); }, 150);" class="px-5 py-2.5 border border-gray-200 text-gray-500 hover:text-gray-700 bg-white hover:bg-gray-50 font-medium rounded-xl transition shadow-sm text-sm">
                    Batal
                </button>
                <button type="submit" class="px-6 py-2.5 bg-rose-600 hover:bg-rose-700 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all text-sm">
                    Tolak Reschedule
                </button>
            </div>
        </form>
    </div>
</div>
@endif
@endsection
