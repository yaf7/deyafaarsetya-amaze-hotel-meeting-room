<x-app-layout>
    <section class="py-16 bg-gray-50 min-h-[80vh] flex items-center">
        <div class="max-w-2xl mx-auto px-4 sm:px-6 lg:px-8 w-full">
            <!-- Payment Card -->
            <div class="card p-8 text-center animate-fade-in">
                <!-- Payment Icon -->
                <div class="w-20 h-20 bg-gradient-to-br from-amber-400 to-amber-600 rounded-full flex items-center justify-center mx-auto mb-6 shadow-lg animate-float">
                    <i class="fas fa-shield-alt text-white text-3xl"></i>
                </div>

                <!-- Title -->
                <h1 class="text-3xl font-bold text-gray-800 mb-2">Pembayaran Aman</h1>
                <p class="text-gray-600 mb-4">Klik tombol di bawah untuk melanjutkan pembayaran via Midtrans</p>

                <!-- Countdown Timer -->
                @php
                    $createdAt = $reservation->created_at;
                    $expiresAt = $createdAt->copy()->addMinutes(20);
                    $remainingSeconds = max(0, now()->diffInSeconds($expiresAt, false));
                @endphp

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
                        <p class="text-red-600 text-sm">Reservasi Anda telah dibatalkan secara otomatis. Silakan buat reservasi baru.</p>
                        <a href="{{ route('home') }}" class="inline-flex items-center gap-2 mt-4 bg-red-600 text-white px-6 py-2.5 rounded-xl font-semibold hover:bg-red-700 transition">
                            <i class="fas fa-home"></i> Kembali ke Beranda
                        </a>
                    </div>
                </div>

                <!-- Reservation Summary -->
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
                            <span class="text-gray-500">Paket</span>
                            <span class="font-semibold text-gray-800">{{ $reservation->foodPackage->name }}</span>
                        </div>

                        @if($reservation->promotion)
                            <div class="flex justify-between items-center text-green-600">
                                <span class="flex items-center gap-2">
                                    <i class="fas fa-tag"></i> Diskon {{ $reservation->promotion->discount }}%
                                </span>
                                <span class="font-medium">-Rp{{ number_format(($reservation->total_price * $reservation->promotion->discount) / 100, 0, ',', '.') }}</span>
                            </div>
                        @endif

                        <div class="flex justify-between items-center pt-4 border-t border-gray-200">
                            <span class="text-gray-700 font-semibold">Total Bayar</span>
                            <span class="text-2xl font-bold text-primary-600">Rp{{ number_format($grossAmount, 0, ',', '.') }}</span>
                        </div>
                    </div>
                </div>

                <!-- Payment Methods Info -->
                <div class="bg-blue-50 rounded-xl p-4 mb-8">
                    <p class="text-sm text-blue-700 font-medium mb-3">
                        <i class="fas fa-info-circle mr-1"></i> Metode pembayaran tersedia:
                    </p>
                    <div class="flex flex-wrap justify-center gap-3">
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-white rounded-lg text-xs font-medium text-gray-700 shadow-sm">
                            <i class="fas fa-qrcode text-blue-500"></i> QRIS
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-white rounded-lg text-xs font-medium text-gray-700 shadow-sm">
                            <i class="fas fa-university text-green-500"></i> Bank Transfer
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-white rounded-lg text-xs font-medium text-gray-700 shadow-sm">
                            <i class="fas fa-wallet text-purple-500"></i> E-Wallet
                        </span>
                        <span class="inline-flex items-center gap-1 px-3 py-1.5 bg-white rounded-lg text-xs font-medium text-gray-700 shadow-sm">
                            <i class="fas fa-credit-card text-amber-500"></i> Kartu Kredit
                        </span>
                    </div>
                </div>

                <!-- Pay Button -->
                <button id="pay-button"
                    class="block w-full bg-gradient-to-r from-amber-500 to-amber-600 text-white text-lg font-semibold py-4 rounded-xl hover:from-amber-600 hover:to-amber-700 transition-all duration-300 shadow-lg hover:shadow-xl transform hover:-translate-y-0.5">
                    <i class="fas fa-lock mr-2"></i>
                    Bayar Sekarang — Rp{{ number_format($grossAmount, 0, ',', '.') }}
                </button>

                <!-- Security Badge -->
                <div class="flex items-center justify-center gap-2 mt-4 text-gray-400 text-sm">
                    <i class="fas fa-shield-alt"></i>
                    <span>Pembayaran diproses dengan aman oleh Midtrans</span>
                </div>

                <!-- Back Link -->
                <a href="{{ route('reservation.confirmation', $reservation->id) }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-primary-600 mt-6 transition-colors">
                    <i class="fas fa-arrow-left"></i>
                    Kembali ke Detail Reservasi
                </a>
            </div>
        </div>
    </section>

    @push('scripts')
    <!-- Midtrans Snap.js -->
    <script src="{{ $isProduction ? 'https://app.midtrans.com/snap/snap.js' : 'https://app.sandbox.midtrans.com/snap/snap.js' }}"
            data-client-key="{{ $clientKey }}"></script>

    <script>
        (function() {
            // Countdown Timer
            let remainingSeconds = {{ (int) $remainingSeconds }};

            const timerMinutes = document.getElementById('timer-minutes');
            const timerSeconds = document.getElementById('timer-seconds');
            const timerContainer = document.getElementById('timer-container');
            const expiredContainer = document.getElementById('expired-container');
            const payButton = document.getElementById('pay-button');

            function updateTimer() {
                if (remainingSeconds <= 0) {
                    // Waktu habis
                    if (timerContainer) timerContainer.classList.add('hidden');
                    if (expiredContainer) expiredContainer.classList.remove('hidden');
                    if (payButton) {
                        payButton.disabled = true;
                        payButton.classList.add('opacity-40', 'cursor-not-allowed');
                        payButton.classList.remove('hover:from-amber-600', 'hover:to-amber-700', 'hover:shadow-xl', 'hover:-translate-y-0.5');
                        payButton.innerHTML = '<i class="fas fa-times-circle mr-2"></i> Waktu Pembayaran Habis';
                    }
                    return;
                }

                const minutes = Math.floor(remainingSeconds / 60);
                const seconds = remainingSeconds % 60;

                if (timerMinutes) timerMinutes.textContent = String(minutes).padStart(2, '0');
                if (timerSeconds) timerSeconds.textContent = String(seconds).padStart(2, '0');

                // Pulse animation when under 1 minute
                if (remainingSeconds <= 60) {
                    const timerBoxes = document.querySelectorAll('#timer-container .bg-red-600');
                    timerBoxes.forEach(box => box.classList.add('animate-pulse'));
                }

                remainingSeconds--;
                setTimeout(updateTimer, 1000);
            }

            updateTimer();

            // Midtrans Pay Button
            payButton.addEventListener('click', function () {
                if (remainingSeconds <= 0) {
                    alert('Maaf, waktu pembayaran sudah habis. Silakan buat reservasi baru.');
                    return;
                }

                var btn = this;
                btn.disabled = true;
                btn.innerHTML = '<i class="fas fa-spinner fa-spin mr-2"></i> Memproses...';

                window.snap.pay('{{ $payment->snap_token }}', {
                    onSuccess: function(result) {
                        window.location.href = '/payment/finish?order_id=' + result.order_id + '&transaction_status=' + result.transaction_status + '&payment_type=' + result.payment_type + '&snap_status=success';
                    },
                    onPending: function(result) {
                        // Di Sandbox, QRIS & Bank Transfer selalu fire onPending
                        // Set snap_status=success karena user sudah menyelesaikan flow pembayaran
                        window.location.href = '/payment/finish?order_id=' + result.order_id + '&transaction_status=' + result.transaction_status + '&payment_type=' + result.payment_type + '&snap_status=success';
                    },
                    onError: function(result) {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-lock mr-2"></i> Bayar Sekarang — Rp{{ number_format($grossAmount, 0, ",", ".") }}';
                        alert('Pembayaran gagal. Silakan coba lagi.');
                    },
                    onClose: function() {
                        btn.disabled = false;
                        btn.innerHTML = '<i class="fas fa-lock mr-2"></i> Bayar Sekarang — Rp{{ number_format($grossAmount, 0, ",", ".") }}';
                    }
                });
            });
        })();
    </script>
    @endpush
</x-app-layout>
