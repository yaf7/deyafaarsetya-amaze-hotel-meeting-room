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
                    <a href="{{ route('rooms.index') }}" class="flex-shrink-0 inline-flex items-center gap-1.5 bg-gray-100 hover:bg-amber-50 text-gray-600 hover:text-amber-600 border border-gray-200 hover:border-amber-300 px-3 py-2 rounded-xl text-xs sm:text-sm font-semibold transition-all duration-200">
                        <i class="fas fa-exchange-alt text-[10px] sm:text-xs"></i>
                        <span class="hidden sm:inline">Ganti Ruangan</span>
                        <span class="sm:hidden">Ganti</span>
                    </a>
                </div>
            </div>

            <!-- Reservation Form -->
            <form action="{{ route('reservation.store') }}" method="POST" id="reservation-form" class="bg-white rounded-2xl shadow-card p-4 sm:p-6 md:p-8">
                @csrf
                <input type="hidden" name="room_id" value="{{ $room->id }}">

                <div class="space-y-5 sm:space-y-6">
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

                        <!-- Session Slot - Card Selector -->
                        <div class="sm:col-span-2">
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-clock text-amber-500 mr-2"></i> Pilih Sesi Waktu
                            </label>
                            <!-- Hidden select for form submission (kept for JS logic compatibility) -->
                            <select name="session_slot" id="session-slot" class="hidden" required>
                                <option value="">-- Pilih Sesi --</option>
                                <option value="Sesi Pagi (08:00 - 12:00)" {{ old('session_slot') == 'Sesi Pagi (08:00 - 12:00)' ? 'selected' : '' }}>Pagi</option>
                                <option value="Sesi Siang (14:00 - 18:00)" {{ old('session_slot') == 'Sesi Siang (14:00 - 18:00)' ? 'selected' : '' }}>Siang</option>
                                <option value="Sesi Malam (18:00 - 22:00)" {{ old('session_slot') == 'Sesi Malam (18:00 - 22:00)' ? 'selected' : '' }}>Malam</option>
                                <option value="Sesi Fullboard (Seharian Penuh)" {{ old('session_slot') == 'Sesi Fullboard (Seharian Penuh)' ? 'selected' : '' }}>Fullboard</option>
                            </select>
                            <input type="hidden" name="session_slot" id="session-slot-hidden" disabled value="">
                            <!-- Visual Card Selector -->
                            <div class="grid grid-cols-2 sm:grid-cols-4 gap-3" id="session-cards">
                                <div class="card-selector" data-session="Sesi Pagi (08:00 - 12:00)" onclick="selectSession(this)">
                                    <span class="card-selector__label">Pagi</span>
                                    <span class="card-selector__sub">08:00 - 12:00</span>
                                    <span class="card-selector__badge bg-green-100 text-green-700 session-badge">Tersedia</span>
                                </div>
                                <div class="card-selector" data-session="Sesi Siang (14:00 - 18:00)" onclick="selectSession(this)">
                                    <span class="card-selector__label">Siang</span>
                                    <span class="card-selector__sub">14:00 - 18:00</span>
                                    <span class="card-selector__badge bg-green-100 text-green-700 session-badge">Tersedia</span>
                                </div>
                                <div class="card-selector" data-session="Sesi Malam (18:00 - 22:00)" onclick="selectSession(this)">
                                    <span class="card-selector__label">Malam</span>
                                    <span class="card-selector__sub">18:00 - 22:00</span>
                                    <span class="card-selector__badge bg-green-100 text-green-700 session-badge">Tersedia</span>
                                </div>
                                <div class="card-selector" data-session="Sesi Fullboard (Seharian Penuh)" onclick="selectSession(this)">
                                    <span class="card-selector__label">Fullboard</span>
                                    <span class="card-selector__sub">Seharian Penuh</span>
                                    <span class="card-selector__badge bg-green-100 text-green-700 session-badge">Tersedia</span>
                                </div>
                            </div>
                            <!-- Info box for session lock -->
                            <div id="session-info-box" class="hidden mt-3 info-box info-box--info">
                                <i class="fas fa-info-circle mt-0.5"></i>
                                <span id="session-info-text"></span>
                            </div>
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

                        <!-- Layout - Card Selector -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-3">
                                <i class="fas fa-th-large text-amber-500 mr-2"></i>Pilih Layout Ruangan
                            </label>
                            <select name="layout" id="layout-select" class="hidden" required>
                                <option value="">-- Pilih Layout --</option>
                                <option value="theater" {{ old('layout') == 'theater' ? 'selected' : '' }}>Theater</option>
                                <option value="classroom" {{ old('layout') == 'classroom' ? 'selected' : '' }}>Classroom</option>
                                <option value="round_table" {{ old('layout') == 'round_table' ? 'selected' : '' }}>Round Table</option>
                                <option value="u_shape" {{ old('layout') == 'u_shape' ? 'selected' : '' }}>U-Shape</option>
                            </select>
                            @php $layoutData = $room->layout; @endphp
                            <div class="grid grid-cols-2 gap-3" id="layout-cards">
                                <div class="card-selector" data-layout="theater" onclick="selectLayout(this)">
                                    <span class="card-selector__label">Theater</span>
                                    <span class="card-selector__sub">Maks {{ $layoutData['theater'] ?? 0 }} orang</span>
                                </div>
                                <div class="card-selector" data-layout="classroom" onclick="selectLayout(this)">
                                    <span class="card-selector__label">Classroom</span>
                                    <span class="card-selector__sub">Maks {{ $layoutData['classroom'] ?? 0 }} orang</span>
                                </div>
                                <div class="card-selector" data-layout="round_table" onclick="selectLayout(this)">
                                    <span class="card-selector__label">Round Table</span>
                                    <span class="card-selector__sub">Maks {{ $layoutData['round_table'] ?? 0 }} orang</span>
                                </div>
                                <div class="card-selector" data-layout="u_shape" onclick="selectLayout(this)">
                                    <span class="card-selector__label">U-Shape</span>
                                    <span class="card-selector__sub">Maks {{ $layoutData['u_shape'] ?? 0 }} orang</span>
                                </div>
                            </div>
                        </div>
                    </div>

                    <!-- Package Section -->
                    <div class="border-t border-gray-200 pt-5 sm:pt-6">
                        <h3 class="text-base sm:text-lg font-bold text-gray-800 mb-1 flex items-center gap-2">
                            <i class="fas fa-utensils text-green-600"></i>
                            Pilih Paket Meeting
                        </h3>
                        <p class="text-xs sm:text-sm text-gray-500 mb-5">Pilih salah satu paket yang sesuai kebutuhan Anda</p>

                        <!-- Paket Reguler -->
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3"><i class="fas fa-box"></i> Paket Reguler</p>
                        <div class="grid grid-cols-1 sm:grid-cols-3 gap-3 mb-5">
                            @foreach ($packages as $pkg)
                                @if (!str_contains(strtolower($pkg->name), 'residential'))
                                    @php
                                        $icons = ['half day' => '<i class="fas fa-coffee text-amber-600"></i>', 'full day' => '<i class="fas fa-utensils text-amber-600"></i>', 'full board' => '<i class="fas fa-concierge-bell text-amber-600"></i>'];
                                        $icon = '<i class="fas fa-box text-amber-600"></i>';
                                        foreach ($icons as $k => $v) { if (str_contains(strtolower($pkg->name), $k)) { $icon = $v; break; } }
                                        preg_match('/(\d+)\s*jam/', $pkg->description, $m);
                                        $dur = isset($m[1]) ? $m[1] . ' jam' : '';
                                        $parts = explode(':', $pkg->description, 2);
                                        $facility = isset($parts[1]) ? trim($parts[1]) : $pkg->description;
                                    @endphp
                                    <label class="package-card has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 cursor-pointer">
                                        <div class="flex items-start gap-3">
                                            <input type="radio" name="food_package_id" value="{{ $pkg->id }}"
                                                data-package-name="{{ $pkg->name }}"
                                                data-package-price="{{ $pkg->price }}"
                                                class="package-radio h-5 w-5 text-amber-600 mt-0.5 flex-shrink-0" required
                                                {{ old('food_package_id') == $pkg->id ? 'checked' : '' }}>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-gray-800 text-sm">{{ $pkg->name }}</span>
                                                </div>
                                                <p class="text-xs text-gray-500 mb-2">{{ $dur }} · {{ $facility }}</p>
                                                <p class="package-price-display font-bold text-amber-600 text-sm">Rp{{ number_format($pkg->price, 0, ',', '.') }}<span class="text-xs font-normal text-gray-400">/pax</span></p>
                                            </div>
                                        </div>
                                    </label>
                                @endif
                            @endforeach
                        </div>

                        <!-- Paket Residential -->
                        <p class="text-xs font-bold text-gray-500 uppercase tracking-wider mb-3"><i class="fas fa-bed"></i> Paket Residential <span class="text-gray-400 font-normal normal-case">(Termasuk Menginap)</span></p>
                        <div class="grid grid-cols-1 sm:grid-cols-2 gap-3">
                            @foreach ($packages as $pkg)
                                @if (str_contains(strtolower($pkg->name), 'residential'))
                                    @php
                                        $icon = '<i class="fas fa-bed text-purple-600"></i>';
                                        preg_match('/(\d+)\s*jam/', $pkg->description, $m);
                                        $dur = isset($m[1]) ? $m[1] . ' jam + 1 malam' : '+ 1 malam';
                                        $parts = explode(':', $pkg->description, 2);
                                        $facility = isset($parts[1]) ? trim($parts[1]) : $pkg->description;
                                        // Base price (meeting only) & room addon
                                        $resBaseMap = [
                                            'Residential Full Day Meeting' => ['base' => 235000, 'twin' => 315000, 'single' => 415000],
                                            'Residential Full Board Meeting' => ['base' => 380000, 'twin' => 220000, 'single' => 320000],
                                        ];
                                        $resInfo = $resBaseMap[$pkg->name] ?? null;
                                        $basePrice = $resInfo ? $resInfo['base'] : $pkg->price;
                                        $roomAddon = $resInfo ? $resInfo['twin'] : 0;
                                    @endphp
                                    <label class="package-card has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50 cursor-pointer">
                                        <div class="flex items-start gap-3">
                                            <input type="radio" name="food_package_id" value="{{ $pkg->id }}"
                                                data-package-name="{{ $pkg->name }}"
                                                data-package-price="{{ $pkg->price }}"
                                                class="package-radio h-5 w-5 text-amber-600 mt-0.5 flex-shrink-0" required
                                                {{ old('food_package_id') == $pkg->id ? 'checked' : '' }}>
                                            <div class="flex-1 min-w-0">
                                                <div class="flex items-center gap-2 mb-1">
                                                    <span class="font-bold text-gray-800 text-sm">{{ $pkg->name }}</span>
                                                </div>
                                                <p class="text-xs text-gray-500 mb-2">{{ $dur }} · {{ $facility }}</p>
                                                <p class="package-price-display font-bold text-amber-600 text-sm">Rp{{ number_format($basePrice, 0, ',', '.') }}<span class="text-xs font-normal text-gray-400">/pax</span></p>
                                                <p class="package-room-addon text-xs text-purple-600 mt-0.5">(+ Rp{{ number_format($roomAddon, 0, ',', '.') }} Kamar Transit)</p>
                                            </div>
                                        </div>
                                    </label>
                                @endif
                            @endforeach
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

    <!-- Sticky Price Bar -->
    <div id="sticky-price-bar" class="sticky-price-bar hidden">
        <div class="max-w-4xl mx-auto flex items-center justify-between gap-4">
            <div class="flex-1 min-w-0">
                <p class="text-xs text-gray-500 truncate">Estimasi Total</p>
                <p class="font-bold text-lg text-amber-600">Rp<span id="sticky-total">0</span></p>
                <p class="text-[10px] text-gray-400 truncate"><span id="sticky-detail">-</span></p>
            </div>
            <button type="button" onclick="document.getElementById('submit-btn').click()" class="btn-secondary py-2.5 px-6 text-sm whitespace-nowrap">
                Lanjut <i class="fas fa-arrow-right ml-1"></i>
            </button>
        </div>
    </div>

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
            const layoutSelect = document.getElementById('layout-select');
            const SESI_PAGI = 'Sesi Pagi (08:00 - 12:00)';
            const SESI_SIANG = 'Sesi Siang (14:00 - 18:00)';
            const SESI_MALAM = 'Sesi Malam (18:00 - 22:00)';
            const SESI_FULLBOARD = 'Sesi Fullboard (Seharian Penuh)';
            const ALL_SESSIONS = [SESI_PAGI, SESI_SIANG, SESI_MALAM, SESI_FULLBOARD];
            let bookedSessions = [];

            // === Card Selectors ===
            window.selectSession = function(el) {
                if (el.classList.contains('card-selector--disabled')) return;
                document.querySelectorAll('#session-cards .card-selector').forEach(c => c.classList.remove('card-selector--active'));
                el.classList.add('card-selector--active');
                sessionSlot.value = el.dataset.session;
                sessionSlotHidden.value = el.dataset.session;
                syncPackageFromSession();
            };
            window.selectLayout = function(el) {
                document.querySelectorAll('#layout-cards .card-selector').forEach(c => c.classList.remove('card-selector--active'));
                el.classList.add('card-selector--active');
                layoutSelect.value = el.dataset.layout;
                layoutSelect.dispatchEvent(new Event('change'));
            };

            // === Layout capacity ===
            layoutSelect.addEventListener('change', function() {
                const layoutData = @json($room->layout);
                const cap = layoutData[this.value] || 0;
                document.getElementById('max-capacity').textContent = cap;
                const pi = document.getElementById('participants-input');
                if (parseInt(pi.value) > cap) pi.value = cap;
            });

            // === Date availability ===
            dateInput.addEventListener('change', function() {
                const d = this.value;
                if (!d) { dateAvailability.classList.add('hidden'); return; }
                dateAvailability.classList.remove('hidden');
                dateAvailableMsg.classList.add('hidden');
                dateUnavailableMsg.classList.add('hidden');
                fetch(`/api/check-availability?room_id=${roomId}&date=${d}`)
                    .then(r => r.json())
                    .then(data => {
                        bookedSessions = data.booked_sessions || [];
                        if (data.available) {
                            dateAvailableMsg.classList.remove('hidden');
                            submitBtn.disabled = false; submitBtn.classList.remove('opacity-50','cursor-not-allowed');
                        } else {
                            dateUnavailableMsg.classList.remove('hidden');
                            submitBtn.disabled = true; submitBtn.classList.add('opacity-50','cursor-not-allowed');
                        }
                        applyBookedSessionRestrictions();
                    }).catch(e => console.error(e));
            });

            // === Session card badges ===
            function updateSessionCards(disabledList) {
                document.querySelectorAll('#session-cards .card-selector').forEach(card => {
                    const val = card.dataset.session;
                    const badge = card.querySelector('.session-badge');
                    if (disabledList.includes(val)) {
                        card.classList.add('card-selector--disabled');
                        card.classList.remove('card-selector--active');
                        if (badge) { badge.textContent = 'Penuh'; badge.className = 'card-selector__badge bg-red-100 text-red-700 session-badge'; }
                    } else {
                        card.classList.remove('card-selector--disabled');
                        if (badge) { badge.textContent = 'Tersedia'; badge.className = 'card-selector__badge bg-green-100 text-green-700 session-badge'; }
                    }
                });
            }

            // === Session restrictions ===
            function applyBookedSessionRestrictions() {
                const pkg = getSelectedPackage();
                if (pkg && isFullDayOrMore(pkg.name)) return;
                enableAllSessionOptions();
                let disabled = [];
                if (pkg && isHalfDay(pkg.name)) { disableSessionOption(SESI_FULLBOARD); disabled.push(SESI_FULLBOARD); }
                bookedSessions.forEach(s => { disableSessionOption(s); disabled.push(s); });
                if (bookedSessions.some(s => s.includes('Siang'))) { disableSessionOption(SESI_MALAM); disabled.push(SESI_MALAM); }
                if (bookedSessions.some(s => s.includes('Malam'))) { disableSessionOption(SESI_SIANG); disabled.push(SESI_SIANG); }
                if (bookedSessions.some(s => s.includes('Fullboard'))) { ALL_SESSIONS.forEach(s => { disableSessionOption(s); disabled.push(s); }); }
                updateSessionCards([...new Set(disabled)]);
                const active = document.querySelector('#session-cards .card-selector--active');
                if (active && active.classList.contains('card-selector--disabled')) { active.classList.remove('card-selector--active'); sessionSlot.value = ''; }
            }

            function syncSessionFromPackage() {
                const pkg = getSelectedPackage();
                const infoBox = document.getElementById('session-info-box');
                const infoText = document.getElementById('session-info-text');
                if (!pkg) { enableAllSessionOptions(); unlockSessionDropdown(); applyBookedSessionRestrictions(); infoBox.classList.add('hidden'); return; }
                if (isHalfDay(pkg.name)) {
                    enableAllSessionOptions(); disableSessionOption(SESI_FULLBOARD); unlockSessionDropdown();
                    applyBookedSessionRestrictions(); infoBox.classList.add('hidden');
                    if (sessionSlot.value === SESI_FULLBOARD) { sessionSlot.value = ''; document.querySelectorAll('#session-cards .card-selector').forEach(c => c.classList.remove('card-selector--active')); }
                } else if (isFullDayOrMore(pkg.name)) {
                    enableAllSessionOptions(); sessionSlot.value = SESI_FULLBOARD; lockSessionDropdown(SESI_FULLBOARD);
                    document.querySelectorAll('#session-cards .card-selector').forEach(c => c.classList.remove('card-selector--active'));
                    const fb = document.querySelector('#session-cards [data-session="' + SESI_FULLBOARD + '"]');
                    if (fb) fb.classList.add('card-selector--active');
                    updateSessionCards([SESI_PAGI, SESI_SIANG, SESI_MALAM]);
                    infoBox.classList.remove('hidden'); infoBox.className = 'mt-3 info-box ' + (isResidential(pkg.name) ? 'info-box--hotel' : 'info-box--info');
                    infoText.textContent = isResidential(pkg.name)
                        ? 'ðŸ¨ Paket Residential menggunakan ruangan seharian penuh dan termasuk 1 malam menginap di Superior Room.'
                        : 'â„¹ï¸ Paket ini menggunakan ruangan seharian penuh. Sesi otomatis disetel ke Fullboard.';
                }
            }

            function syncPackageFromSession() {
                document.querySelectorAll('.package-radio').forEach(r => { r.disabled = false; const l = r.closest('label'); if (l) l.classList.remove('opacity-40','pointer-events-none'); });
                calculateTotal();
            }

            function enableAllSessionOptions() { sessionSlot.querySelectorAll('option').forEach(o => o.disabled = false); }
            function disableSessionOption(v) { const o = sessionSlot.querySelector(`option[value="${v}"]`); if (o) o.disabled = true; }
            function lockSessionDropdown(v) { sessionSlot.value = v; sessionSlot.disabled = true; sessionSlotHidden.value = v; sessionSlotHidden.disabled = false; }
            function unlockSessionDropdown() { sessionSlot.disabled = false; sessionSlotHidden.disabled = true; }

            // === Price helpers ===
            const PRICE_MAP = { 'Residential Full Day Meeting': { twin: 550000, single: 650000 }, 'Residential Full Board Meeting': { twin: 600000, single: 700000 } };
            const CALC_MAP = { 'Residential Full Day Meeting': { base: 235000, twin_addon: 315000, single_addon: 415000 }, 'Residential Full Board Meeting': { base: 380000, twin_addon: 220000, single_addon: 320000 } };
            const residentialSection = document.getElementById('residential-type-section');
            const priceEstimation = document.getElementById('price-estimation');

            function isResidential(n) { return n && n.toLowerCase().includes('residential'); }
            function isHalfDay(n) { return n && n.toLowerCase().includes('half day'); }
            function isFullDayOrMore(n) { if (!n) return false; const l = n.toLowerCase(); return l.includes('full day') || l.includes('full board') || l.includes('residential'); }
            function getSelectedPackage() { const c = document.querySelector('input[name="food_package_id"]:checked'); return c ? { id: c.value, name: c.dataset.packageName, price: parseInt(c.dataset.packagePrice) } : null; }
            function getResidentialType() { const c = document.querySelector('input[name="residential_type"]:checked'); return c ? c.value : 'twin'; }
            function formatRupiah(n) { return n.toString().replace(/\B(?=(\d{3})+(?!\d))/g, '.'); }

            function updatePriceDisplay() {
                const pkg = getSelectedPackage(); if (!pkg) return;
                if (isResidential(pkg.name) && CALC_MAP[pkg.name]) {
                    const resType = getResidentialType();
                    const calc = CALC_MAP[pkg.name];
                    const basePrice = calc.base;
                    const roomAddon = resType === 'twin' ? calc.twin_addon : calc.single_addon;
                    document.querySelectorAll('input[name="food_package_id"]').forEach(r => {
                        if (r.dataset.packageName === pkg.name) {
                            const l = r.closest('label');
                            if (l) {
                                const p = l.querySelector('.package-price-display');
                                if (p) p.innerHTML = 'Rp' + formatRupiah(basePrice) + '<span class="text-xs font-normal text-gray-400">/pax</span>';
                                const a = l.querySelector('.package-room-addon');
                                if (a) a.textContent = '(+ Rp' + formatRupiah(roomAddon) + ' Kamar Transit)';
                            }
                        }
                    });
                }
            }

            function calculateTotal() {
                const pkg = getSelectedPackage();
                const participants = parseInt(document.getElementById('participants-input').value) || 0;
                const stickyBar = document.getElementById('sticky-price-bar');
                if (!pkg || participants < 1) { priceEstimation.classList.add('hidden'); stickyBar.classList.add('hidden'); return; }
                priceEstimation.classList.remove('hidden'); stickyBar.classList.remove('hidden');
                let total = 0, pricePerPax = pkg.price, roomAddon = 0;
                const estRoomAddon = document.getElementById('est-room-addon');
                if (isResidential(pkg.name) && CALC_MAP[pkg.name]) {
                    const rt = getResidentialType(), calc = CALC_MAP[pkg.name];
                    pricePerPax = calc.base; roomAddon = rt === 'twin' ? calc.twin_addon : calc.single_addon;
                    total = (calc.base * participants) + roomAddon;
                    estRoomAddon.classList.remove('hidden'); document.getElementById('est-room-cost').textContent = formatRupiah(roomAddon);
                } else { total = pkg.price * participants; estRoomAddon.classList.add('hidden'); }
                document.getElementById('est-package-name').textContent = pkg.name;
                document.getElementById('est-price-per-pax').textContent = formatRupiah(pricePerPax);
                document.getElementById('est-participants').textContent = participants;
                document.getElementById('est-total').textContent = formatRupiah(total);
                document.getElementById('sticky-total').textContent = formatRupiah(total);
                document.getElementById('sticky-detail').textContent = `${participants} pax Ã— ${pkg.name}`;
            }

            // === Events ===
            document.querySelectorAll('.package-radio').forEach(r => r.addEventListener('change', function() {
                if (isResidential(this.dataset.packageName)) {
                    residentialSection.classList.remove('hidden');
                    document.querySelectorAll('.residential-radio').forEach(radio => {
                        radio.disabled = false;
                    });
                    if (!document.querySelector('input[name="residential_type"]:checked')) {
                        document.getElementById('res-twin').checked = true;
                    }
                } else {
                    residentialSection.classList.add('hidden');
                    document.querySelectorAll('.residential-radio').forEach(radio => {
                        radio.disabled = true;
                        radio.checked = false;
                    });
                }
                syncSessionFromPackage(); updatePriceDisplay(); calculateTotal();
            }));
            sessionSlot.addEventListener('change', syncPackageFromSession);
            document.querySelectorAll('.residential-radio').forEach(r => r.addEventListener('change', () => { updatePriceDisplay(); calculateTotal(); }));
            document.getElementById('participants-input').addEventListener('input', calculateTotal);

            // === Pre-fill from URL (availability board) ===
            const urlParams = new URLSearchParams(window.location.search);
            if (urlParams.get('date')) { dateInput.value = urlParams.get('date'); dateInput.dispatchEvent(new Event('change')); }
            if (urlParams.get('session')) { setTimeout(() => { const c = document.querySelector(`#session-cards [data-session="${urlParams.get('session')}"]`); if (c && !c.classList.contains('card-selector--disabled')) selectSession(c); }, 600); }

            // === Init ===
            const initPkg = document.querySelector('input[name="food_package_id"]:checked');
            if (initPkg) initPkg.dispatchEvent(new Event('change'));
            if (sessionSlot.value) syncPackageFromSession();
            if (layoutSelect.value) { const lc = document.querySelector(`#layout-cards [data-layout="${layoutSelect.value}"]`); if (lc) lc.classList.add('card-selector--active'); layoutSelect.dispatchEvent(new Event('change')); }
            if (sessionSlot.value) { const sc = document.querySelector(`#session-cards [data-session="${sessionSlot.value}"]`); if (sc) sc.classList.add('card-selector--active'); }
        </script>
    @endpush
</x-app-layout>
