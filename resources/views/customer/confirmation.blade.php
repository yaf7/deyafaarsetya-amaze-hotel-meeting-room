<x-app-layout>
    <section class="py-16 bg-gray-50 min-h-[80vh] flex items-center">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <!-- Success Card -->
            <div class="card p-8 text-center animate-fade-in">
                <!-- Success Icon -->
                <div class="w-20 h-20 bg-gradient-to-br from-secondary-400 to-secondary-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg animate-float">
                    <i class="fas fa-check text-white text-3xl"></i>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Reservasi Berhasil!</h1>
                <p class="text-gray-600 mb-4">Silakan lanjutkan pembayaran untuk mengkonfirmasi booking Anda</p>

                <!-- Countdown Timer -->
                @php
                    $createdAt = $reservation->created_at;
                    $expiresAt = $createdAt->copy()->addMinutes(20);
                    $remainingSeconds = max(0, now()->diffInSeconds($expiresAt, false));
                @endphp

                @if($reservation->status === 'pending' && $reservation->payment && $reservation->payment->payment_status === 'pending')
                    <div class="mb-6" id="timer-container">
                        <div class="bg-red-50 border border-red-200 rounded-xl p-4">
                            <div class="flex items-center justify-center gap-2 mb-2">
                                <i class="fas fa-exclamation-triangle text-red-500"></i>
                                <span class="text-red-700 font-semibold text-sm">Batas Waktu Pembayaran</span>
                            </div>
                            <div class="flex items-center justify-center gap-3">
                                <div class="bg-red-600 text-white rounded-lg px-4 py-2 min-w-[60px]">
                                    <span id="timer-minutes" class="text-2xl font-bold">00</span>
                                    <p class="text-[10px] uppercase tracking-wider opacity-80">Menit</p>
                                </div>
                                <span class="text-red-600 text-2xl font-bold">:</span>
                                <div class="bg-red-600 text-white rounded-lg px-4 py-2 min-w-[60px]">
                                    <span id="timer-seconds" class="text-2xl font-bold">00</span>
                                    <p class="text-[10px] uppercase tracking-wider opacity-80">Detik</p>
                                </div>
                            </div>
                            <p class="text-xs text-red-500 mt-2">Reservasi akan otomatis dibatalkan jika tidak dibayar</p>
                        </div>
                    </div>

                    <!-- Expired Message (hidden by default) -->
                    <div class="mb-6 hidden" id="expired-container">
                        <div class="bg-red-50 border border-red-200 rounded-xl p-6">
                            <div class="w-14 h-14 bg-red-100 rounded-full flex items-center justify-center mx-auto mb-3">
                                <i class="fas fa-times-circle text-red-500 text-2xl"></i>
                            </div>
                            <h3 class="text-red-700 font-bold text-lg mb-1">Waktu Pembayaran Habis</h3>
                            <p class="text-red-600 text-sm">Reservasi Anda telah dibatalkan secara otomatis karena tidak melakukan pembayaran dalam 20 menit.</p>
                            <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-4 bg-red-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-red-700 transition">
                                <i class="fas fa-home"></i> Kembali ke Beranda
                            </a>
                        </div>
                    </div>
                @endif

                <!-- Reservation Details -->
                <div class="bg-gray-50 rounded-2xl p-6 text-left mb-8">
                    <div class="space-y-4">
                        <div class="flex justify-between items-center pb-3 border-b border-gray-200">
                            <span class="text-gray-500">ID Reservasi</span>
                            <span class="font-bold text-primary-600 text-lg">#{{ str_pad($reservation->id, 6, '0', STR_PAD_LEFT) }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Nama Pemesan</span>
                            <span class="font-semibold text-gray-800">{{ $reservation->customer_name }}</span>
                        </div>

                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Nomor Telepon</span>
                            <span class="font-semibold text-gray-800">{{ $reservation->phone }}</span>
                        </div>
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Ruangan</span>
                            <span class="font-semibold text-gray-800">{{ $reservation->meetingRoom->name }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Tanggal</span>
                            <span class="font-semibold text-gray-800">{{ \Carbon\Carbon::parse($reservation->date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Waktu</span>
                            <span class="font-semibold text-gray-800">{{ $reservation->time }}</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Peserta</span>
                            <span class="font-semibold text-gray-800">{{ $reservation->participants }} orang</span>
                        </div>
                        
                        <div class="flex justify-between items-center">
                            <span class="text-gray-500">Paket</span>
                            <span class="font-semibold text-gray-800">{{ $reservation->foodPackage->name }}</span>
                        </div>
                        
                        <!-- Fasilitas Ruangan -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-building text-blue-600"></i>
                                Fasilitas Ruangan
                            </h3>
                            <div class="grid grid-cols-1 gap-1.5 text-sm">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-tv text-blue-500 text-xs w-4 text-center"></i>
                                    <span class="text-gray-700">LCD Projector + Screen</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-volume-up text-blue-500 text-xs w-4 text-center"></i>
                                    <span class="text-gray-700">Sound System</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-chalkboard text-blue-500 text-xs w-4 text-center"></i>
                                    <span class="text-gray-700">Flipchart & Writing Materials</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-tint text-blue-500 text-xs w-4 text-center"></i>
                                    <span class="text-gray-700">Air Mineral</span>
                                </div>
                            </div>
                        </div>

                        <!-- Menu Include -->
                        <div class="pt-4 border-t border-gray-200">
                            <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                <i class="fas fa-gift text-amber-600"></i>
                                Menu Include
                            </h3>
                            <div class="grid grid-cols-1 gap-1.5 text-sm">
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-bowl-rice text-amber-500 text-xs w-4 text-center"></i>
                                    <span class="text-gray-700">Nasi Putih</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-apple-whole text-amber-500 text-xs w-4 text-center"></i>
                                    <span class="text-gray-700">2 Kind of Slice Fruit</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-cake-candles text-amber-500 text-xs w-4 text-center"></i>
                                    <span class="text-gray-700">Assorted Dessert</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-glass-water text-amber-500 text-xs w-4 text-center"></i>
                                    <span class="text-gray-700">Any Kind Juice</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-bottle-water text-amber-500 text-xs w-4 text-center"></i>
                                    <span class="text-gray-700">Mineral Dispenser</span>
                                </div>
                                <div class="flex items-center gap-2">
                                    <i class="fas fa-mug-hot text-amber-500 text-xs w-4 text-center"></i>
                                    <span class="text-gray-700">Coffee Break</span>
                                </div>
                            </div>
                            <p class="text-xs text-gray-500 mt-1.5 ml-6 italic">Terdiri dari kopi, teh, dan 2 jenis snack pilihan chef</p>
                        </div>

                        <!-- Pilihan Menu Buffet -->
                        @if($reservation->buffetSelections->count() > 0)
                            <div class="pt-4 border-t border-gray-200">
                                <h3 class="font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                    <i class="fas fa-utensils text-amber-600"></i>
                                    Pilihan Menu Buffet Anda
                                </h3>
                                <div class="space-y-2 text-sm">
                                    @foreach($reservation->buffetSelections as $selection)
                                        <div class="flex items-start gap-2">
                                            <i class="fas fa-check-circle text-green-600 mt-0.5"></i>
                                            <div>
                                                <span class="text-gray-500 text-xs uppercase">{{ ucfirst($selection->buffetMenu->category) }}:</span>
                                                <span class="text-gray-800 font-medium ml-1">{{ $selection->buffetMenu->name }}</span>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                                <div class="mt-3 p-3 bg-blue-50 rounded-lg text-xs text-gray-600">
                                    <i class="fas fa-info-circle text-blue-600 mr-1"></i>
                                    Menu lain seperti Nasi Putih, Buah, Dessert, Juice, Air Mineral, dan Coffee Break sudah termasuk dalam paket.
                                </div>
                            </div>
                        @endif
                        
                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <span class="text-gray-700 font-semibold">Total Pembayaran</span>
                            <span class="text-2xl font-bold text-primary-600">Rp{{ number_format($reservation->total_price, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Status Badge -->
                <div class="inline-flex items-center gap-2 px-6 py-3 bg-amber-50 text-amber-700 rounded-xl font-semibold mb-8" id="status-badge">
                    <i class="fas fa-clock animate-pulse"></i>
                    <span>Status: Menunggu Pembayaran</span>
                </div>

                <!-- CTA Button -->
                <a href="{{ route('payment.pay', $reservation->id) }}"
                   class="block w-full btn-secondary text-lg py-4" id="pay-btn">
                    <i class="fas fa-credit-card mr-2"></i>
                    Bayar Sekarang
                </a>

                <!-- Back Link -->
                <a href="{{ route('home') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary-600 mt-6 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Beranda
                </a>
            </div>
        </div>
    </section>

    @if($reservation->status === 'pending' && $reservation->payment && $reservation->payment->payment_status === 'pending')
    <script>
        (function() {
            let remainingSeconds = {{ (int) $remainingSeconds }};

            const timerMinutes = document.getElementById('timer-minutes');
            const timerSeconds = document.getElementById('timer-seconds');
            const timerContainer = document.getElementById('timer-container');
            const expiredContainer = document.getElementById('expired-container');
            const payBtn = document.getElementById('pay-btn');
            const statusBadge = document.getElementById('status-badge');

            function updateTimer() {
                if (remainingSeconds <= 0) {
                    // Waktu habis
                    if (timerContainer) timerContainer.classList.add('hidden');
                    if (expiredContainer) expiredContainer.classList.remove('hidden');
                    if (payBtn) {
                        payBtn.classList.add('pointer-events-none', 'opacity-40');
                        payBtn.innerHTML = '<i class="fas fa-times-circle mr-2"></i> Waktu Pembayaran Habis';
                    }
                    if (statusBadge) {
                        statusBadge.className = 'inline-flex items-center gap-2 px-6 py-3 bg-red-50 text-red-700 rounded-xl font-semibold mb-8';
                        statusBadge.innerHTML = '<i class="fas fa-times-circle"></i><span>Status: Dibatalkan (Timeout)</span>';
                    }
                    return;
                }

                const minutes = Math.floor(remainingSeconds / 60);
                const seconds = remainingSeconds % 60;

                if (timerMinutes) timerMinutes.textContent = String(minutes).padStart(2, '0');
                if (timerSeconds) timerSeconds.textContent = String(seconds).padStart(2, '0');

                // Warna berubah jika sisa waktu < 5 menit
                if (remainingSeconds <= 300) {
                    const timerBoxes = document.querySelectorAll('#timer-container .bg-red-600');
                    timerBoxes.forEach(box => {
                        if (remainingSeconds <= 60) {
                            box.classList.add('animate-pulse');
                        }
                    });
                }

                remainingSeconds--;
                setTimeout(updateTimer, 1000);
            }

            updateTimer();
        })();
    </script>
    @endif
</x-app-layout>
