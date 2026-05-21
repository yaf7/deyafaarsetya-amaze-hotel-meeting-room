<x-app-layout>
    <!-- Header Section -->
    <section class="bg-gradient-hero py-12 relative overflow-hidden">
        <div class="floating-decoration w-48 h-48 bg-white/10 -top-10 -right-10"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-2xl sm:text-3xl md:text-4xl font-bold text-white mb-2">Susun Komposisi Buffet Anda</h1>
                <p class="text-white/80">{{ $room->name }} - {{ $package->name }}</p>
            </div>
        </div>

        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 40" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0 40L60 35C120 30 240 20 360 15C480 10 600 15 720 20C840 25 960 30 1080 30C1200 30 1320 25 1380 22.5L1440 20V40H0Z"
                    fill="#f9fafb" />
            </svg>
        </div>
    </section>

    <!-- Buffet Selection Section -->
    <section class="py-8 sm:py-12 bg-gray-50">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Ringkasan Data Reservasi -->
            <div class="bg-white rounded-2xl shadow-card p-5 sm:p-6 md:p-8 border border-gray-100/60 mb-6 sm:mb-8">
                <div class="flex items-center gap-3 mb-5 pb-4 border-b border-gray-100">
                    <span class="inline-flex items-center justify-center w-9 h-9 rounded-full bg-amber-500 text-white shadow-sm">
                        <i class="fas fa-clipboard-list text-sm"></i>
                    </span>
                    <div>
                        <h2 class="text-base sm:text-lg font-bold text-gray-800">Ringkasan Reservasi Anda</h2>
                        <p class="text-[11px] sm:text-xs text-gray-500">Data yang sudah Anda isi sebelumnya</p>
                    </div>
                    <a href="{{ route('reservation.form', $reservationData['room_id']) }}" class="ml-auto flex-shrink-0 inline-flex items-center gap-1.5 bg-gray-100 hover:bg-amber-50 text-gray-600 hover:text-amber-600 border border-gray-200 hover:border-amber-300 px-3 py-2 rounded-xl text-xs font-semibold transition-all duration-200">
                        <i class="fas fa-edit text-[10px]"></i>
                        <span class="hidden sm:inline">Ubah Data</span>
                        <span class="sm:hidden">Ubah</span>
                    </a>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-4 sm:gap-5">
                    <!-- Ruangan -->
                    <div class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-blue-100 text-blue-600 flex-shrink-0 mt-0.5">
                            <i class="fas fa-door-open text-sm"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider font-medium">Ruangan</p>
                            <p class="text-sm font-semibold text-gray-800 truncate">{{ $room->name }}</p>
                        </div>
                    </div>

                    <!-- Tanggal -->
                    <div class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 flex-shrink-0 mt-0.5">
                            <i class="fas fa-calendar text-sm"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider font-medium">Tanggal Acara</p>
                            <p class="text-sm font-semibold text-gray-800">{{ \Carbon\Carbon::parse($reservationData['date'])->translatedFormat('l, d F Y') }}</p>
                        </div>
                    </div>

                    <!-- Sesi Waktu -->
                    <div class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-purple-100 text-purple-600 flex-shrink-0 mt-0.5">
                            <i class="fas fa-clock text-sm"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider font-medium">Sesi Waktu</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $reservationData['time'] }}</p>
                        </div>
                    </div>

                    <!-- Jumlah Peserta -->
                    <div class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-orange-100 text-orange-600 flex-shrink-0 mt-0.5">
                            <i class="fas fa-users text-sm"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider font-medium">Jumlah Peserta</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $reservationData['participants'] }} orang</p>
                        </div>
                    </div>

                    <!-- Layout -->
                    <div class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-teal-100 text-teal-600 flex-shrink-0 mt-0.5">
                            <i class="fas fa-th-large text-sm"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider font-medium">Layout Ruangan</p>
                            <p class="text-sm font-semibold text-gray-800">{{ ucfirst(str_replace('_', ' ', $reservationData['layout'])) }}</p>
                        </div>
                    </div>

                    <!-- Paket Meeting -->
                    <div class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-amber-100 text-amber-600 flex-shrink-0 mt-0.5">
                            <i class="fas fa-utensils text-sm"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider font-medium">Paket Meeting</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $package->name }}</p>
                        </div>
                    </div>

                    @if($reservationData['residential_type'])
                    <!-- Tipe Kamar -->
                    <div class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-indigo-100 text-indigo-600 flex-shrink-0 mt-0.5">
                            <i class="fas fa-bed text-sm"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider font-medium">Tipe Kamar</p>
                            <p class="text-sm font-semibold text-gray-800">{{ $reservationData['residential_type'] === 'twin' ? 'Twin Sharing' : 'Single Occupancy' }}</p>
                        </div>
                    </div>
                    @endif

                    @if($promotion)
                    <!-- Promo -->
                    <div class="flex items-start gap-3">
                        <span class="inline-flex items-center justify-center w-8 h-8 rounded-lg bg-green-100 text-green-600 flex-shrink-0 mt-0.5">
                            <i class="fas fa-tag text-sm"></i>
                        </span>
                        <div class="min-w-0">
                            <p class="text-[11px] text-gray-400 uppercase tracking-wider font-medium">Promo</p>
                            <p class="text-sm font-semibold text-green-700">{{ $promotion->name }} <span class="text-xs font-normal text-green-500">({{ number_format($promotion->discount, 0) }}%)</span></p>
                        </div>
                    </div>
                    @endif
                </div>
            </div>

            <!-- Komposisi Buffet Standar -->
            <div class="card p-6 mb-8">
                <h2 class="text-xl font-bold text-gray-800 mb-4 flex items-center gap-2">
                    <i class="fas fa-list-check text-amber-600"></i>
                    Komposisi Buffet Standar
                </h2>

                <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                    <div class="space-y-2">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-1"></i>
                            <div>
                                <span class="font-semibold">Soup</span>
                                <span class="text-gray-500 text-sm ml-2">(Pilih 1 menu)</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-1"></i>
                            <div>
                                <span class="font-semibold">Nasi Putih</span>
                                <span class="text-gray-500 text-sm ml-2">(Sudah termasuk)</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-1"></i>
                            <div>
                                <span class="font-semibold">Sayuran</span>
                                <span class="text-gray-500 text-sm ml-2">(Pilih 1 menu)</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-1"></i>
                            <div>
                                <span class="font-semibold">Ayam / Ikan</span>
                                <span class="text-gray-500 text-sm ml-2">(Pilih masing-masing 1 menu)</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-1"></i>
                            <div>
                                <span class="font-semibold">Fritter</span>
                                <span class="text-gray-500 text-sm ml-2">(Pilih 1 menu)</span>
                            </div>
                        </div>
                    </div>

                    <div class="space-y-2">
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-1"></i>
                            <div>
                                <span class="font-semibold">Mie</span>
                                <span class="text-gray-500 text-sm ml-2">(Pilih 1 menu)</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-1"></i>
                            <div>
                                <span class="font-semibold">2 Kind of Slice Fruit</span>
                                <span class="text-gray-500 text-sm ml-2">(Sudah termasuk)</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-1"></i>
                            <div>
                                <span class="font-semibold">Assorted Dessert</span>
                                <span class="text-gray-500 text-sm ml-2">(Sudah termasuk)</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-1"></i>
                            <div>
                                <span class="font-semibold">Any Kind Juice</span>
                                <span class="text-gray-500 text-sm ml-2">(Sudah termasuk)</span>
                            </div>
                        </div>
                        <div class="flex items-start gap-2">
                            <i class="fas fa-check text-green-600 mt-1"></i>
                            <div>
                                <span class="font-semibold">Mineral Dispenser</span>
                                <span class="text-gray-500 text-sm ml-2">(Sudah termasuk)</span>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Coffee Break Info -->
                <div class="mt-6 p-4 bg-amber-50 border-l-4 border-amber-500 rounded-r-lg">
                    <div class="flex items-start gap-3">
                        <i class="fas fa-coffee text-amber-600 text-xl mt-0.5"></i>
                        <div>
                            <h3 class="font-semibold text-gray-800 mb-1">Coffee Break</h3>
                            <p class="text-sm text-gray-600">Terdiri dari kopi, teh, dan 2 jenis snack pilihan chef
                                (Sudah termasuk)</p>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Form Pemilihan Menu -->
            <form action="{{ route('reservation.buffet.store') }}" method="POST" class="space-y-6">
                @csrf

                @if ($errors->any())
                    <div class="bg-red-50 border-l-4 border-red-500 p-4 rounded-r-lg">
                        <div class="flex items-start gap-3">
                            <i class="fas fa-exclamation-circle text-red-500 text-xl mt-0.5"></i>
                            <div>
                                <h3 class="font-semibold text-red-800 mb-2">Perhatian!</h3>
                                <ul class="list-disc list-inside space-y-1 text-sm text-red-700">
                                    @foreach ($errors->all() as $error)
                                        <li>{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        </div>
                    </div>
                @endif

                <!-- Menu Soup -->
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        Menu Soup <span class="text-red-500 text-sm">(Pilih 1 menu)</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($menus['soup'] as $menu)
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-amber-400 transition-colors has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                                <input type="radio" name="soup" value="{{ $menu->id }}"
                                    class="h-4 w-4 text-amber-600" required>
                                <span class="ml-3 text-sm text-gray-800">{{ $menu->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Menu Mie -->
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        Menu Mie <span class="text-red-500 text-sm">(Pilih 1 menu)</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($menus['mie'] as $menu)
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-amber-400 transition-colors has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                                <input type="radio" name="mie" value="{{ $menu->id }}"
                                    class="h-4 w-4 text-amber-600" required>
                                <span class="ml-3 text-sm text-gray-800">{{ $menu->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Menu Ayam -->
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        Menu Ayam <span class="text-red-500 text-sm">(Pilih 1 menu)</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($menus['ayam'] as $menu)
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-amber-400 transition-colors has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                                <input type="radio" name="ayam" value="{{ $menu->id }}"
                                    class="h-4 w-4 text-amber-600" required>
                                <span class="ml-3 text-sm text-gray-800">{{ $menu->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Menu Ikan -->
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        Menu Ikan <span class="text-red-500 text-sm">(Pilih 1 menu)</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($menus['ikan'] as $menu)
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-amber-400 transition-colors has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                                <input type="radio" name="ikan" value="{{ $menu->id }}"
                                    class="h-4 w-4 text-amber-600" required>
                                <span class="ml-3 text-sm text-gray-800">{{ $menu->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Menu Sayuran -->
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        Menu Sayuran <span class="text-red-500 text-sm">(Pilih 1 menu)</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($menus['sayuran'] as $menu)
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-amber-400 transition-colors has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                                <input type="radio" name="sayuran" value="{{ $menu->id }}"
                                    class="h-4 w-4 text-amber-600" required>
                                <span class="ml-3 text-sm text-gray-800">{{ $menu->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Menu Fritter -->
                <div class="card p-6">
                    <h3 class="text-lg font-bold text-gray-800 mb-4 flex items-center gap-2">
                        Menu Fritter <span class="text-red-500 text-sm">(Pilih 1 menu)</span>
                    </h3>
                    <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-3">
                        @foreach ($menus['fritter'] as $menu)
                            <label
                                class="flex items-center p-3 border-2 border-gray-200 rounded-lg cursor-pointer hover:border-amber-400 transition-colors has-[:checked]:border-amber-500 has-[:checked]:bg-amber-50">
                                <input type="radio" name="fritter" value="{{ $menu->id }}"
                                    class="h-4 w-4 text-amber-600" required>
                                <span class="ml-3 text-sm text-gray-800">{{ $menu->name }}</span>
                            </label>
                        @endforeach
                    </div>
                </div>

                <!-- Submit Button -->
                <div class="flex gap-4">
                    <a href="{{ route('reservation.form', $reservationData['room_id']) }}"
                        class="flex-1 btn-outline text-center py-3">
                        <i class="fas fa-arrow-left mr-2"></i>
                        Kembali
                    </a>
                    <button type="submit" class="flex-1 btn-secondary py-3">
                        <i class="fas fa-arrow-right mr-2"></i>
                        Lanjut ke Konfirmasi
                    </button>
                </div>
            </form>
        </div>
    </section>
</x-app-layout>
