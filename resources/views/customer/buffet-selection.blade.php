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
