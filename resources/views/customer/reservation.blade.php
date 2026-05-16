<x-app-layout>
    <!-- Header Section -->
    <section class="bg-gradient-hero py-12 relative overflow-hidden">
        <div class="floating-decoration w-48 h-48 bg-white/10 -top-10 -right-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-2">Form Reservasi</h1>
                <p class="text-white/80">{{ $room->name }}</p>
            </div>
        </div>
        
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 40L60 35C120 30 240 20 360 15C480 10 600 15 720 20C840 25 960 30 1080 30C1200 30 1320 25 1380 22.5L1440 20V40H0Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    <!-- Form Section -->
    <section class="py-8 sm:py-12 bg-gray-50">
        <div class="max-w-4xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Error Messages -->
            @if ($errors->any())
                <div class="mb-6 bg-red-50 border border-red-200 rounded-xl p-4">
                    <div class="flex items-center gap-2 mb-2">
                        <i class="fas fa-exclamation-circle text-red-500"></i>
                        <span class="font-semibold text-red-700">Terjadi kesalahan:</span>
                    </div>
                    <ul class="list-disc list-inside text-sm text-red-600 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Room Preview Card -->
            <div class="card p-4 sm:p-6 mb-6 sm:mb-8">
                <div class="flex items-center gap-3 sm:gap-4">
                    <div class="w-16 h-16 sm:w-20 sm:h-20 rounded-xl overflow-hidden flex-shrink-0">
                        <img src="{{ $room->photo ? asset('storage/' . $room->photo) : 'https://images.unsplash.com/photo-1517502884422-41eaead166d4?w=200&h=200&fit=crop' }}"
                             alt="{{ $room->name }}"
                             class="w-full h-full object-cover">
                    </div>
                    <div class="min-w-0 flex-1">
                        <h2 class="text-lg sm:text-xl font-bold text-gray-800 truncate">{{ $room->name }}</h2>
                        <p class="text-gray-500 text-xs sm:text-sm line-clamp-2">{{ $room->facilities }}</p>
                    </div>
                </div>
            </div>

            <!-- Reservation Form -->
            <form action="{{ route('reservation.store') }}" method="POST" id="reservation-form" class="bg-white rounded-2xl shadow-card p-4 sm:p-6 md:p-8">
                @csrf
                <input type="hidden" name="room_id" value="{{ $room->id }}">

                <div class="space-y-5 sm:space-y-6">
                    <!-- Nama & Telepon Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Nama Pemesan -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-user text-amber-500 mr-2"></i>Nama Pemesan
                            </label>
                            <input type="text" name="customer_name"
                                class="input-modern"
                                placeholder="Masukkan nama lengkap"
                                required value="{{ old('customer_name') }}">
                        </div>

                        <!-- Nomor Telepon -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-phone text-amber-500 mr-2"></i>Nomor Telepon
                            </label>
                            <input type="tel" name="phone"
                                class="input-modern"
                                placeholder="Contoh: 08123456789"
                                required value="{{ old('phone') }}">
                        </div>
                    </div>

                    <!-- Date & Session Slot Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Date -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-calendar text-amber-500 mr-2"></i>Tanggal Acara
                            </label>
                            <input type="date" name="date" id="date-input"
                                class="input-modern"
                                required min="{{ date('Y-m-d') }}"
                                value="{{ old('date') }}">
                            <!-- Availability status message -->
                            <div id="date-availability" class="mt-2 hidden">
                                <p id="date-available-msg" class="text-sm text-green-600 hidden">
                                    <i class="fas fa-check-circle mr-1"></i>Ruangan tersedia pada tanggal ini
                                </p>
                                <p id="date-unavailable-msg" class="text-sm text-red-600 hidden">
                                    <i class="fas fa-times-circle mr-1"></i>Ruangan sudah dipesan pada tanggal ini. Silakan pilih tanggal lain.
                                </p>
                            </div>
                        </div>

                        <!-- Session Slot (menggantikan Waktu Mulai) -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock text-amber-500 mr-2"></i>Sesi Waktu
                            </label>
                            <select name="session_slot" id="session-slot" class="input-modern" required>
                                <option value="">-- Pilih Sesi --</option>
                                <option value="Sesi Pagi (08:00 - 12:00)" {{ old('session_slot') == 'Sesi Pagi (08:00 - 12:00)' ? 'selected' : '' }}>Sesi Pagi (08:00 - 12:00)</option>
                                <option value="Sesi Siang (14:00 - 18:00)" {{ old('session_slot') == 'Sesi Siang (14:00 - 18:00)' ? 'selected' : '' }}>Sesi Siang (14:00 - 18:00)</option>
                                <option value="Sesi Malam (18:00 - 22:00)" {{ old('session_slot') == 'Sesi Malam (18:00 - 22:00)' ? 'selected' : '' }}>Sesi Malam (18:00 - 22:00)</option>
                                <option value="Sesi Fullboard (Seharian Penuh)" {{ old('session_slot') == 'Sesi Fullboard (Seharian Penuh)' ? 'selected' : '' }}>Sesi Fullboard (Seharian Penuh)</option>
                            </select>
                            <!-- Hidden input: digunakan saat dropdown dikunci (disabled) agar value tetap terkirim -->
                            <input type="hidden" name="session_slot" id="session-slot-hidden" disabled value="">
                        </div>
                    </div>

                    <!-- Participants & Layout Row -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-4 sm:gap-6">
                        <!-- Participants -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-users text-amber-500 mr-2"></i>Jumlah Peserta
                            </label>
                            <input type="number" name="participants" id="participants-input"
                                class="input-modern"
                                min="1" value="{{ old('participants', 1) }}" required>
                            <p class="text-xs sm:text-sm text-gray-500 mt-2">
                                Maksimal: <span id="max-capacity" class="font-semibold text-amber-600">...</span> orang
                            </p>
                        </div>

                        <!-- Layout -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-th-large text-amber-500 mr-2"></i>Layout Ruangan
                            </label>
                            <select name="layout" id="layout-select" class="input-modern" required>
                                <option value="">-- Pilih Layout --</option>
                                <option value="theater" {{ old('layout') == 'theater' ? 'selected' : '' }}>Theater</option>
                                <option value="classroom" {{ old('layout') == 'classroom' ? 'selected' : '' }}>Classroom</option>
                                <option value="round_table" {{ old('layout') == 'round_table' ? 'selected' : '' }}>Round Table</option>
                                <option value="u_shape" {{ old('layout') == 'u_shape' ? 'selected' : '' }}>U-Shape</option>
                            </select>
                        </div>
                    </div>

                    <!-- Package Section -->
                    <div class="border-t border-gray-200 pt-5 sm:pt-6">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                            <i class="fas fa-utensils text-green-600"></i>
                            Pilih Paket Meeting
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-4">Pilih salah satu paket catering</p>

                        <!-- Mobile: Card Layout -->
                        <div class="block sm:hidden space-y-3">
                            @foreach ($packages as $pkg)
                                <label for="package-mobile-{{ $pkg->id }}" 
                                       class="block p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-amber-400 transition-colors has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                                    <div class="flex items-start gap-3">
                                        <input type="radio" name="food_package_id" value="{{ $pkg->id }}"
                                            id="package-mobile-{{ $pkg->id }}"
                                            data-package-name="{{ $pkg->name }}"
                                            data-package-price="{{ $pkg->price }}"
                                            class="package-radio h-5 w-5 text-amber-600 mt-0.5" required
                                            {{ old('food_package_id') == $pkg->id ? 'checked' : '' }}>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start gap-2">
                                                <span class="font-semibold text-gray-800">{{ $pkg->name }}</span>
                                                <span class="package-price-display font-bold text-amber-600 text-sm whitespace-nowrap">Rp{{ number_format($pkg->price, 0, ',', '.') }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">{{ $pkg->description }}</p>
                                        </div>
                                    </div>
                                </label>
                            @endforeach
                        </div>

                        <!-- Desktop: Table Layout -->
                        <div class="hidden sm:block table-modern overflow-x-auto">
                            <table class="w-full">
                                <thead>
                                    <tr>
                                        <th class="w-12"></th>
                                        <th>Jenis Paket</th>
                                        <th>Durasi</th>
                                        <th class="hidden lg:table-cell">Fasilitas</th>
                                        <th class="text-right">Harga/Pax</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($packages as $pkg)
                                        @php
                                            $isResidential = str_contains(strtolower($pkg->name), 'residential');
                                            $duration = '';
                                            $facilities = $pkg->description;
                                            if (preg_match('/(\d+)\s*jam/', $pkg->description, $m)) {
                                                $duration = $m[1] . ' jam';
                                            }
                                            if ($isResidential) {
                                                $duration .= '<br><span class="text-xs text-red-500 font-medium">+ Menginap</span>';
                                            }
                                            $parts = explode(':', $pkg->description, 2);
                                            $facilityText = isset($parts[1]) ? trim($parts[1]) : $pkg->description;
                                        @endphp
                                        <tr class="cursor-pointer hover:bg-amber-50" onclick="document.getElementById('package-{{ $pkg->id }}').click()">
                                            <td class="text-center">
                                                <input type="radio" name="food_package_id" value="{{ $pkg->id }}"
                                                    id="package-{{ $pkg->id }}"
                                                    data-package-name="{{ $pkg->name }}"
                                                    data-package-price="{{ $pkg->price }}"
                                                    class="package-radio h-5 w-5 text-amber-600 focus:ring-amber-500 cursor-pointer" required
                                                    {{ old('food_package_id') == $pkg->id ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <label for="package-{{ $pkg->id }}" class="font-semibold text-gray-800 cursor-pointer">
                                                    {{ $pkg->name }}
                                                </label>
                                            </td>
                                            <td class="text-gray-600">{!! $duration !!}</td>
                                            <td class="hidden lg:table-cell text-gray-600 text-sm">{{ $facilityText }}</td>
                                            <td class="text-right">
                                                <span class="package-price-display font-bold text-amber-600">Rp{{ number_format($pkg->price, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>

                    <!-- Residential Type Section (hidden by default) -->
                    <div id="residential-type-section" class="hidden border-t border-gray-200 pt-5 sm:pt-6">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-3 sm:mb-4 flex items-center gap-2">
                            <i class="fas fa-bed text-blue-600"></i>
                            Tipe Kamar Menginap
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-600 mb-4">Pilih tipe kamar untuk paket residential</p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                            <label for="res-twin" class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="residential_type" value="twin" id="res-twin"
                                    class="residential-radio h-5 w-5 text-blue-600"
                                    {{ old('residential_type', 'twin') == 'twin' ? 'checked' : '' }}>
                                <div>
                                    <span class="font-semibold text-gray-800">Twin Sharing</span>
                                    <p class="text-xs text-gray-500 mt-0.5">1 kamar untuk 2 orang</p>
                                </div>
                            </label>
                            <label for="res-single" class="flex items-center gap-3 p-4 border-2 border-gray-200 rounded-xl cursor-pointer hover:border-blue-400 transition-colors has-[:checked]:border-blue-500 has-[:checked]:bg-blue-50">
                                <input type="radio" name="residential_type" value="single" id="res-single"
                                    class="residential-radio h-5 w-5 text-blue-600"
                                    {{ old('residential_type') == 'single' ? 'checked' : '' }}>
                                <div>
                                    <span class="font-semibold text-gray-800">Single Occupancy</span>
                                    <p class="text-xs text-gray-500 mt-0.5">1 kamar untuk 1 orang</p>
                                </div>
                            </label>
                        </div>
                    </div>

                    <!-- Estimasi Total Harga -->
                    <div id="price-estimation" class="hidden border-t border-gray-200 pt-5 sm:pt-6">
                        <div class="bg-gradient-to-r from-amber-50 to-orange-50 rounded-xl p-5 border border-amber-200">
                            <h3 class="text-base font-bold text-gray-800 mb-3 flex items-center gap-2">
                                <i class="fas fa-calculator text-amber-600"></i>
                                Estimasi Total Harga
                            </h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Paket: <span id="est-package-name" class="font-medium text-gray-800">-</span></span>
                                    <span class="text-gray-600">Rp<span id="est-price-per-pax">0</span> /pax</span>
                                </div>
                                <div class="flex justify-between items-center">
                                    <span class="text-gray-600">Jumlah Peserta</span>
                                    <span class="font-medium text-gray-800"><span id="est-participants">0</span> orang</span>
                                </div>
                                <div id="est-room-addon" class="hidden flex justify-between items-center text-blue-600">
                                    <span>Biaya Kamar Transit Panitia</span>
                                    <span class="font-medium">+ Rp<span id="est-room-cost">0</span></span>
                                </div>
                                <div class="border-t border-amber-300 pt-2 mt-2">
                                    <div class="flex justify-between items-center">
                                        <span class="font-bold text-gray-800 text-base">Total Estimasi</span>
                                        <span class="font-bold text-amber-600 text-xl">Rp<span id="est-total">0</span></span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Promo Section -->
                    <div class="border-t border-gray-200 pt-5 sm:pt-6">
                        <label class="block text-sm font-semibold text-gray-700 mb-2">
                            <i class="fas fa-tag text-orange-500 mr-2"></i>Gunakan Promo (Opsional)
                        </label>
                        <select name="promotion_id" class="input-modern">
                            <option value="">Tidak Pakai Promo</option>
                            @foreach ($promotions as $promo)
                                <option value="{{ $promo->id }}" {{ old('promotion_id') == $promo->id ? 'selected' : '' }}>
                                    {{ $promo->name }} (Diskon {{ number_format($promo->discount, 2) }}%)
                                </option>
                            @endforeach
                        </select>
                        <p class="text-xs sm:text-sm text-gray-500 mt-2">
                            <i class="fas fa-info-circle text-gray-400 mr-1"></i>
                            Diskon akan diterapkan di halaman konfirmasi
                        </p>
                    </div>

                    <!-- Submit Button -->
                    <div class="pt-3 sm:pt-4">
                        <button type="submit" id="submit-btn" class="w-full btn-secondary text-base sm:text-lg py-3 sm:py-4">
                            <i class="fas fa-arrow-right mr-2"></i>
                            Lanjut ke Pemilihan Menu Buffet
                        </button>
                    </div>
                </div>
            </form>
        </div>
    </section>

    @push('scripts')
        <script>
            const roomId = {{ $room->id }};
            const dateInput = document.getElementById('date-input');
            const submitBtn = document.getElementById('submit-btn');
            const dateAvailability = document.getElementById('date-availability');
            const dateAvailableMsg = document.getElementById('date-available-msg');
            const dateUnavailableMsg = document.getElementById('date-unavailable-msg');
            const sessionSlot = document.getElementById('session-slot');
            const sessionSlotHidden = document.getElementById('session-slot-hidden');

            // === Sesi & Paket Constants ===
            const SESI_PAGI = 'Sesi Pagi (08:00 - 12:00)';
            const SESI_SIANG = 'Sesi Siang (14:00 - 18:00)';
            const SESI_MALAM = 'Sesi Malam (18:00 - 22:00)';
            const SESI_FULLBOARD = 'Sesi Fullboard (Seharian Penuh)';

            // Sesi yang sudah dipesan pada tanggal terpilih
            let bookedSessions = [];

            // === Cek ketersediaan saat tanggal dipilih ===
            dateInput.addEventListener('change', function() {
                const selectedDate = this.value;
                if (!selectedDate) {
                    dateAvailability.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    return;
                }

                dateAvailability.classList.remove('hidden');
                dateAvailableMsg.classList.add('hidden');
                dateUnavailableMsg.classList.add('hidden');

                fetch(`/api/check-availability?room_id=${roomId}&date=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        dateAvailability.classList.remove('hidden');
                        // Simpan sesi yang sudah dipesan
                        bookedSessions = data.booked_sessions || [];

                        if (data.available) {
                            dateAvailableMsg.classList.remove('hidden');
                            dateUnavailableMsg.classList.add('hidden');
                            submitBtn.disabled = false;
                            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                        } else {
                            dateAvailableMsg.classList.add('hidden');
                            dateUnavailableMsg.classList.remove('hidden');
                            submitBtn.disabled = true;
                            submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                        }

                        // Apply booked session restrictions ke dropdown sesi
                        applyBookedSessionRestrictions();
                    })
                    .catch(error => {
                        console.error('Error checking availability:', error);
                    });
            });

            // === Layout capacity ===
            document.getElementById('layout-select').addEventListener('change', function() {
                const layoutData = @json($room->layout);
                const cap = layoutData[this.value] || 0;
                document.getElementById('max-capacity').textContent = cap;
                const participantsInput = document.getElementById('participants-input');
                if (parseInt(participantsInput.value) > cap) {
                    participantsInput.value = cap;
                }
            });
            document.getElementById('layout-select').dispatchEvent(new Event('change'));

            // === Harga paket residential dinamis ===
            const PRICE_MAP = {
                'Residential Full Day Meeting': { twin: 550000, single: 650000 },
                'Residential Full Board Meeting': { twin: 600000, single: 700000 }
            };

            const CALC_MAP = {
                'Residential Full Day Meeting': { base: 235000, twin_addon: 315000, single_addon: 415000 },
                'Residential Full Board Meeting': { base: 380000, twin_addon: 220000, single_addon: 320000 }
            };

            const residentialSection = document.getElementById('residential-type-section');
            const priceEstimation = document.getElementById('price-estimation');

            function isResidential(name) {
                return name && name.toLowerCase().includes('residential');
            }

            function isHalfDay(name) {
                return name && name.toLowerCase().includes('half day');
            }

            function isFullDayOrMore(name) {
                if (!name) return false;
                const n = name.toLowerCase();
                return n.includes('full day') || n.includes('full board') || n.includes('residential');
            }

            function getSelectedPackage() {
                const checked = document.querySelector('input[name="food_package_id"]:checked');
                if (!checked) return null;
                return {
                    id: checked.value,
                    name: checked.dataset.packageName,
                    price: parseInt(checked.dataset.packagePrice)
                };
            }

            function getResidentialType() {
                const checked = document.querySelector('input[name="residential_type"]:checked');
                return checked ? checked.value : 'twin';
            }

            function formatRupiah(num) {
                return num.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.');
            }

            // === Session ↔ Package Interlocking ===

            /**
             * Disable sesi yang sudah dipesan + terapkan aturan konflik Siang↔Malam
             */
            function applyBookedSessionRestrictions() {
                const pkg = getSelectedPackage();

                // Jika paket Full Day+ sudah dikunci ke Fullboard, tidak perlu atur lagi
                if (pkg && isFullDayOrMore(pkg.name)) return;

                // Enable semua dulu, lalu disable sesuai aturan
                enableAllSessionOptions();

                // Selalu disable Fullboard untuk Half Day
                if (pkg && isHalfDay(pkg.name)) {
                    disableSessionOption(SESI_FULLBOARD);
                }

                // Disable sesi yang sudah dipesan
                bookedSessions.forEach(s => {
                    disableSessionOption(s);
                });

                // Aturan konflik: Siang & Malam tidak boleh bersamaan
                const hasSiang = bookedSessions.some(s => s.includes('Siang'));
                const hasMalam = bookedSessions.some(s => s.includes('Malam'));
                if (hasSiang) disableSessionOption(SESI_MALAM);
                if (hasMalam) disableSessionOption(SESI_SIANG);

                // Jika ada Fullboard yang sudah dipesan, semua sesi di-disable
                const hasFullboard = bookedSessions.some(s => s.includes('Fullboard'));
                if (hasFullboard) {
                    disableSessionOption(SESI_PAGI);
                    disableSessionOption(SESI_SIANG);
                    disableSessionOption(SESI_MALAM);
                    disableSessionOption(SESI_FULLBOARD);
                }

                // Reset pilihan jika sesi terpilih sekarang di-disable
                const currentOpt = sessionSlot.querySelector(`option[value="${sessionSlot.value}"]`);
                if (currentOpt && currentOpt.disabled) {
                    sessionSlot.value = '';
                }
            }

            /**
             * Saat PAKET berubah → atur opsi dropdown sesi
             */
            function syncSessionFromPackage() {
                const pkg = getSelectedPackage();
                if (!pkg) {
                    enableAllSessionOptions();
                    unlockSessionDropdown();
                    applyBookedSessionRestrictions();
                    return;
                }

                if (isHalfDay(pkg.name)) {
                    // Half Day: Pagi, Siang, Malam terbuka (Fullboard di-disable)
                    enableAllSessionOptions();
                    disableSessionOption(SESI_FULLBOARD);
                    unlockSessionDropdown();

                    // Terapkan restricsi dari sesi yg sudah dipesan
                    applyBookedSessionRestrictions();

                    // Reset jika sesi terpilih adalah Fullboard
                    if (sessionSlot.value === SESI_FULLBOARD) {
                        sessionSlot.value = '';
                    }
                } else if (isFullDayOrMore(pkg.name)) {
                    // Full Day / Full Board / Residential: paksa Fullboard & kunci
                    enableAllSessionOptions();
                    sessionSlot.value = SESI_FULLBOARD;
                    lockSessionDropdown(SESI_FULLBOARD);
                }
            }

            /**
             * Saat SESI berubah → semua paket tetap bisa dipilih
             */
            function syncPackageFromSession() {
                // Enable semua paket untuk semua sesi (Pagi, Siang, Malam)
                document.querySelectorAll('.package-radio').forEach(radio => {
                    radio.disabled = false;
                    const container = radio.closest('tr') || radio.closest('label');
                    if (container) container.classList.remove('opacity-40', 'pointer-events-none');
                });

                calculateTotal();
            }

            function enableAllSessionOptions() {
                sessionSlot.querySelectorAll('option').forEach(opt => {
                    opt.disabled = false;
                    opt.classList.remove('hidden');
                });
            }

            function disableSessionOption(value) {
                const opt = sessionSlot.querySelector(`option[value="${value}"]`);
                if (opt) {
                    opt.disabled = true;
                }
            }

            function lockSessionDropdown(value) {
                sessionSlot.value = value;
                sessionSlot.disabled = true;
                // Use hidden input to still submit the value
                sessionSlotHidden.value = value;
                sessionSlotHidden.disabled = false;
            }

            function unlockSessionDropdown() {
                sessionSlot.disabled = false;
                // Disable hidden input so it doesn't override
                sessionSlotHidden.disabled = true;
            }

            // === Price Display & Estimation ===

            function updatePriceDisplay() {
                const pkg = getSelectedPackage();
                if (!pkg) return;

                if (isResidential(pkg.name) && PRICE_MAP[pkg.name]) {
                    const resType = getResidentialType();
                    const displayPrice = PRICE_MAP[pkg.name][resType];

                    document.querySelectorAll('input[name="food_package_id"]').forEach(radio => {
                        if (radio.dataset.packageName === pkg.name) {
                            const label = radio.closest('tr') || radio.closest('label');
                            if (label) {
                                const priceEl = label.querySelector('.package-price-display');
                                if (priceEl) priceEl.textContent = 'Rp' + formatRupiah(displayPrice);
                            }
                        }
                    });
                }
            }

            function calculateTotal() {
                const pkg = getSelectedPackage();
                const participants = parseInt(document.getElementById('participants-input').value) || 0;

                if (!pkg || participants < 1) {
                    priceEstimation.classList.add('hidden');
                    return;
                }

                priceEstimation.classList.remove('hidden');

                let total = 0;
                let pricePerPax = pkg.price;
                let roomAddon = 0;
                const estRoomAddon = document.getElementById('est-room-addon');

                if (isResidential(pkg.name) && CALC_MAP[pkg.name]) {
                    const resType = getResidentialType();
                    const calc = CALC_MAP[pkg.name];
                    pricePerPax = PRICE_MAP[pkg.name][resType];
                    roomAddon = resType === 'twin' ? calc.twin_addon : calc.single_addon;
                    total = (calc.base * participants) + roomAddon;

                    estRoomAddon.classList.remove('hidden');
                    document.getElementById('est-room-cost').textContent = formatRupiah(roomAddon);
                } else {
                    total = pkg.price * participants;
                    estRoomAddon.classList.add('hidden');
                }

                document.getElementById('est-package-name').textContent = pkg.name;
                document.getElementById('est-price-per-pax').textContent = formatRupiah(pricePerPax);
                document.getElementById('est-participants').textContent = participants;
                document.getElementById('est-total').textContent = formatRupiah(total);
            }

            // === Event Listeners ===

            // Paket dipilih → sync sesi + residential + harga
            document.querySelectorAll('.package-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    const name = this.dataset.packageName;
                    if (isResidential(name)) {
                        residentialSection.classList.remove('hidden');
                    } else {
                        residentialSection.classList.add('hidden');
                    }
                    syncSessionFromPackage();
                    updatePriceDisplay();
                    calculateTotal();
                });
            });

            // Sesi dipilih → sync paket
            sessionSlot.addEventListener('change', function() {
                syncPackageFromSession();
            });

            // Residential type berubah
            document.querySelectorAll('.residential-radio').forEach(radio => {
                radio.addEventListener('change', function() {
                    updatePriceDisplay();
                    calculateTotal();
                });
            });

            // Jumlah peserta berubah
            document.getElementById('participants-input').addEventListener('input', calculateTotal);

            // Init on page load (untuk old() values)
            const initialPkg = document.querySelector('input[name="food_package_id"]:checked');
            if (initialPkg) {
                initialPkg.dispatchEvent(new Event('change'));
            }
            if (sessionSlot.value) {
                syncPackageFromSession();
            }
        </script>
    @endpush
</x-app-layout>
