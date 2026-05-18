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

        <!-- Konfirmasi WhatsApp -->
        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
            <div class="flex items-center gap-3 mb-4">
                <div class="w-10 h-10 {{ $reservation->whatsapp_sent ? 'bg-blue-100' : 'bg-green-100' }} rounded-xl flex items-center justify-center">
                    <i class="fab fa-whatsapp {{ $reservation->whatsapp_sent ? 'text-blue-600' : 'text-green-600' }} text-xl"></i>
                </div>
                <div>
                    <h2 class="text-lg font-bold text-gray-800">Konfirmasi WhatsApp</h2>
                </div>
            </div>
            
            @if($reservation->whatsapp_sent)
                <a href="{{ route('admin.reservation.whatsapp', $reservation->id) }}"
                   class="w-full bg-white border-2 border-green-500 hover:bg-green-50 text-green-600 px-4 py-3 rounded-xl font-semibold transition-colors flex items-center justify-center gap-2 mt-4">
                    <i class="fab fa-whatsapp text-lg"></i>
                    Buka Halaman WhatsApp
                </a>
            @else
                <a href="{{ route('admin.reservation.whatsapp', $reservation->id) }}"
                   class="w-full bg-green-500 hover:bg-green-600 text-white px-4 py-3 rounded-xl font-semibold transition-colors flex items-center justify-center gap-2 mt-4">
                    <i class="fab fa-whatsapp text-lg"></i>
                    Buka Halaman WhatsApp
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
@endsection
