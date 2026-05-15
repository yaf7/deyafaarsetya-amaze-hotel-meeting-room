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

                    <!-- Date & Time Row -->
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

                        <!-- Time -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                <i class="fas fa-clock text-amber-500 mr-2"></i>Waktu Mulai
                            </label>
                            <input type="time" name="time"
                                class="input-modern"
                                required value="{{ old('time') }}">
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
                                            class="h-5 w-5 text-amber-600 mt-0.5" required
                                            {{ old('food_package_id') == $pkg->id ? 'checked' : '' }}>
                                        <div class="flex-1 min-w-0">
                                            <div class="flex justify-between items-start gap-2">
                                                <span class="font-semibold text-gray-800">{{ $pkg->name }}</span>
                                                <span class="font-bold text-amber-600 text-sm whitespace-nowrap">Rp{{ number_format($pkg->price, 0, ',', '.') }}</span>
                                            </div>
                                            <p class="text-xs text-gray-500 mt-1">
                                                @php
                                                    if (preg_match('/(\d+)\s*j[a-z]+/', $pkg->description, $matches)) {
                                                        echo $matches[1] . ' jam';
                                                    }
                                                    if (strpos($pkg->description, 'menginap') !== false) {
                                                        echo ' + 1 malam menginap';
                                                    }
                                                @endphp
                                            </p>
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
                                        <tr class="cursor-pointer hover:bg-amber-50" onclick="document.getElementById('package-{{ $pkg->id }}').click()">
                                            <td class="text-center">
                                                <input type="radio" name="food_package_id" value="{{ $pkg->id }}"
                                                    id="package-{{ $pkg->id }}"
                                                    class="h-5 w-5 text-amber-600 focus:ring-amber-500 cursor-pointer" required
                                                    {{ old('food_package_id') == $pkg->id ? 'checked' : '' }}>
                                            </td>
                                            <td>
                                                <label for="package-{{ $pkg->id }}" class="font-semibold text-gray-800 cursor-pointer">
                                                    {{ $pkg->name }}
                                                </label>
                                            </td>
                                            <td class="text-gray-600">
                                                @php
                                                    if (preg_match('/(\d+)\s*j[a-z]+/', $pkg->description, $matches)) {
                                                        echo $matches[1] . ' jam';
                                                    } else {
                                                        echo '-';
                                                    }
                                                    if (strpos($pkg->description, 'menginap') !== false) {
                                                        echo '<br><span class="text-xs text-red-500 font-medium">+ 1 malam menginap</span>';
                                                    }
                                                @endphp
                                            </td>
                                            <td class="hidden lg:table-cell text-gray-600 text-sm">
                                                @php
                                                    $parts = explode(':', $pkg->description, 2);
                                                    echo isset($parts[1]) ? trim($parts[1]) : $pkg->description;
                                                @endphp
                                            </td>
                                            <td class="text-right">
                                                <span class="font-bold text-amber-600">Rp{{ number_format($pkg->price, 0, ',', '.') }}</span>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
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

            // Cek ketersediaan saat tanggal dipilih
            dateInput.addEventListener('change', function() {
                const selectedDate = this.value;
                if (!selectedDate) {
                    dateAvailability.classList.add('hidden');
                    submitBtn.disabled = false;
                    submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    return;
                }

                // Tampilkan loading
                dateAvailability.classList.remove('hidden');
                dateAvailableMsg.classList.add('hidden');
                dateUnavailableMsg.classList.add('hidden');

                fetch(`/api/check-availability?room_id=${roomId}&date=${selectedDate}`)
                    .then(response => response.json())
                    .then(data => {
                        dateAvailability.classList.remove('hidden');
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
                    })
                    .catch(error => {
                        console.error('Error checking availability:', error);
                    });
            });

            // Layout capacity
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
        </script>
    @endpush
</x-app-layout>
