<x-app-layout>
    <section class="py-16 bg-gray-50 min-h-[80vh]">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Invoice Header -->
            <div class="text-center mb-8 animate-fade-in">
                <div class="inline-flex items-center gap-3 mb-4">
                    <img src="{{ asset('storage/rooms/amazelogo.png') }}" alt="Amaze Hotel Logo" class="h-14 w-auto">
                    <div class="text-left">
                        <h2 class="text-xl font-bold text-gray-800">Amaze Hotel</h2>
                        <p class="text-gray-500 text-sm">Kediri</p>
                    </div>
                </div>
                <h1 class="text-3xl font-bold text-gray-800">INVOICE RESERVASI</h1>
            </div>

            <!-- Invoice Card -->
            <div class="card p-8 animate-slide-up">
                <!-- Success Status -->
                <div class="flex items-center gap-3 p-4 bg-gradient-to-r from-secondary-50 to-secondary-100 rounded-xl mb-8 border border-secondary-200">
                    <div class="w-10 h-10 bg-secondary-500 rounded-full flex items-center justify-center flex-shrink-0">
                        <i class="fas fa-check text-white"></i>
                    </div>
                    <div>
                        <p class="font-bold text-secondary-700">PEMBAYARAN BERHASIL</p>
                        <p class="text-secondary-600 text-sm">Reservasi Anda telah dikonfirmasi</p>
                    </div>
                </div>

                <!-- Reservation Info -->
                <div class="grid grid-cols-2 gap-4 mb-8">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-500 text-sm mb-1">Nomor Reservasi</p>
                        <p class="font-bold text-primary-600 text-lg">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</p>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <p class="text-gray-500 text-sm mb-1">Tanggal Reservasi</p>
                        <p class="font-semibold text-gray-800">{{ \Carbon\Carbon::now()->locale('id')->isoFormat('D MMM Y') }}</p>
                    </div>
                </div>

                <!-- Divider with Title -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-px bg-gray-200 flex-1"></div>
                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Detail Reservasi</span>
                    <div class="h-px bg-gray-200 flex-1"></div>
                </div>

                <!-- Reservation Details -->
                <div class="space-y-4 mb-8">
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 flex items-center gap-2">
                            <i class="fas fa-user text-primary-400"></i> Nama Pemesan
                        </span>
                        <span class="font-semibold text-gray-800">{{ $reservation->customer_name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 flex items-center gap-2">
                            <i class="fas fa-phone text-primary-400"></i> Nomor Telepon
                        </span>
                        <span class="font-semibold text-gray-800">{{ $reservation->phone }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 flex items-center gap-2">
                            <i class="fas fa-door-open text-primary-400"></i> Ruangan
                        </span>
                        <span class="font-semibold text-gray-800">{{ $reservation->meetingRoom->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 flex items-center gap-2">
                            <i class="fas fa-utensils text-primary-400"></i> Paket Meeting
                        </span>
                        <span class="font-semibold text-gray-800">{{ $reservation->foodPackage->name }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 flex items-center gap-2">
                            <i class="fas fa-calendar text-primary-400"></i> Tanggal Acara
                        </span>
                        <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($reservation->date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 flex items-center gap-2">
                            <i class="fas fa-clock text-primary-400"></i> Waktu Mulai
                        </span>
                        <span class="font-semibold text-gray-800">{{ $reservation->time }}</span>
                    </div>
                    <div class="flex justify-between items-center py-3 border-b border-gray-100">
                        <span class="text-gray-600 flex items-center gap-2">
                            <i class="fas fa-users text-primary-400"></i> Jumlah Peserta
                        </span>
                        <span class="font-semibold text-gray-800">{{ $reservation->participants }} orang</span>
                    </div>
                </div>

                <!-- Fasilitas Ruangan -->
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-px bg-gray-200 flex-1"></div>
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Fasilitas Ruangan</span>
                        <div class="h-px bg-gray-200 flex-1"></div>
                    </div>
                    <div class="bg-blue-50 rounded-xl p-4 border border-blue-100">
                        <ul class="space-y-1.5 text-sm text-gray-700">
                            <li class="flex items-center gap-2"><i class="fas fa-tv text-blue-400 text-xs w-4 text-center"></i> LCD Projector + Screen</li>
                            <li class="flex items-center gap-2"><i class="fas fa-volume-up text-blue-400 text-xs w-4 text-center"></i> Sound System</li>
                            <li class="flex items-center gap-2"><i class="fas fa-chalkboard text-blue-400 text-xs w-4 text-center"></i> Flipchart & Writing Materials</li>
                            <li class="flex items-center gap-2"><i class="fas fa-tint text-blue-400 text-xs w-4 text-center"></i> Air Mineral</li>
                        </ul>
                    </div>
                </div>

                <!-- Menu Include -->
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-px bg-gray-200 flex-1"></div>
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Menu Include</span>
                        <div class="h-px bg-gray-200 flex-1"></div>
                    </div>
                    <div class="bg-amber-50 rounded-xl p-4 border border-amber-100">
                        <ul class="space-y-1.5 text-sm text-gray-700">
                            <li class="flex items-center gap-2"><i class="fas fa-bowl-rice text-amber-400 text-xs w-4 text-center"></i> Nasi Putih</li>
                            <li class="flex items-center gap-2"><i class="fas fa-apple-whole text-amber-400 text-xs w-4 text-center"></i> 2 Kind of Slice Fruit</li>
                            <li class="flex items-center gap-2"><i class="fas fa-cake-candles text-amber-400 text-xs w-4 text-center"></i> Assorted Dessert</li>
                            <li class="flex items-center gap-2"><i class="fas fa-glass-water text-amber-400 text-xs w-4 text-center"></i> Any Kind Juice</li>
                            <li class="flex items-center gap-2"><i class="fas fa-bottle-water text-amber-400 text-xs w-4 text-center"></i> Mineral Dispenser</li>
                            <li class="flex items-center gap-2"><i class="fas fa-mug-hot text-amber-400 text-xs w-4 text-center"></i> Coffee Break</li>
                        </ul>
                        <p class="text-xs text-gray-500 mt-1.5 italic">Terdiri dari kopi, teh, dan 2 jenis snack pilihan chef</p>
                    </div>
                </div>

                <!-- Pilihan Menu Buffet -->
                @if($reservation->buffetSelections && $reservation->buffetSelections->count() > 0)
                <div class="mb-8">
                    <div class="flex items-center gap-4 mb-4">
                        <div class="h-px bg-gray-200 flex-1"></div>
                        <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Pilihan Menu Buffet</span>
                        <div class="h-px bg-gray-200 flex-1"></div>
                    </div>
                    <div class="space-y-2">
                        @foreach($reservation->buffetSelections as $selection)
                            <div class="flex items-start gap-2 text-sm">
                                <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                                <div>
                                    <span class="text-gray-500 text-xs uppercase">{{ ucfirst($selection->buffetMenu->category) }}:</span>
                                    <span class="text-gray-800 font-medium ml-1">{{ $selection->buffetMenu->name }}</span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
                @endif

                <!-- Divider with Title -->
                <div class="flex items-center gap-4 mb-6">
                    <div class="h-px bg-gray-200 flex-1"></div>
                    <span class="text-sm font-semibold text-gray-500 uppercase tracking-wider">Rincian Pembayaran</span>
                    <div class="h-px bg-gray-200 flex-1"></div>
                </div>

                <!-- Payment Details -->
                <div class="space-y-3 mb-6">
                    <div class="flex justify-between items-center">
                        <span class="text-gray-600">Total Harga</span>
                        <span class="font-medium text-gray-800">Rp{{ number_format($reservation->total_price, 0, ',', '.') }}</span>
                    </div>

                    @if($reservation->promotion_id)
                        @php
                            $discountPercent = $reservation->promotion->discount;
                            $discountAmount = ($reservation->total_price * $discountPercent) / 100;
                        @endphp
                        <div class="flex justify-between items-center text-secondary-600">
                            <span class="flex items-center gap-2">
                                <i class="fas fa-tag"></i>
                                Diskon {{ $discountPercent }}% ({{ $reservation->promotion->name }})
                            </span>
                            <span class="font-medium">-Rp{{ number_format($discountAmount, 0, ',', '.') }}</span>
                        </div>
                    @endif
                </div>

                <!-- Total -->
                <div class="bg-gradient-to-r from-primary-600 to-primary-500 rounded-xl p-5 text-white">
                    <div class="flex justify-between items-center">
                        <span class="text-lg font-semibold text-white/90">Total Bayar</span>
                        <span class="text-3xl font-bold">
                            @php
                                $discountPercent = $reservation->promotion?->discount ?? 0;
                                $discountAmount = ($reservation->total_price * $discountPercent) / 100;
                            @endphp
                            Rp{{ number_format(
                                $reservation->total_price - $discountAmount,
                                0,
                                ',',
                                '.'
                            ) }}
                        </span>
                    </div>
                </div>

                <!-- Footer Info -->
                <div class="mt-8 text-center">
                    <div class="inline-flex items-center gap-2 text-gray-400 text-sm mb-2">
                        <i class="fas fa-heart text-red-400"></i>
                        <span>Terima kasih telah memilih Amaze Hotel Kediri!</span>
                    </div>
                    <p class="text-gray-500 text-sm">Silakan simpan invoice ini sebagai bukti reservasi.</p>
                </div>
            </div>

            <!-- Action Buttons -->
            <div class="flex flex-col sm:flex-row gap-4 mt-8 justify-center">
                <button onclick="window.print()" class="btn-outline flex items-center justify-center gap-2">
                    <i class="fas fa-print"></i>
                    Cetak Invoice
                </button>
                <a href="{{ url('/') }}" class="btn-primary flex items-center justify-center gap-2">
                    <i class="fas fa-home"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>
</x-app-layout>
