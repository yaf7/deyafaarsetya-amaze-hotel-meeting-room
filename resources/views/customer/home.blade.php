<x-app-layout>
    <!-- Hero Section with Gradient -->
    <section class="relative bg-gradient-hero min-h-[70vh] flex items-center overflow-hidden">
        <!-- Decorative Elements -->
        <div class="floating-decoration w-96 h-96 bg-white/10 -top-20 -left-20"></div>
        <div class="floating-decoration w-64 h-64 bg-secondary-500/20 top-1/2 right-0"></div>
        <div class="floating-decoration w-80 h-80 bg-primary-300/20 bottom-0 left-1/3"></div>

        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-20 relative z-10">
            <div class="text-center">
                <!-- Badge -->
                <div
                    class="inline-flex items-center gap-2 px-4 py-2 bg-white/10 backdrop-blur-sm rounded-full text-white/90 text-sm font-medium mb-6 animate-fade-in">
                    <i class="fas fa-star text-yellow-400"></i>
                    <span>Hotel Meeting Room #1 di Kediri</span>
                </div>

                <!-- Main Title -->
                <h1 class="text-4xl md:text-6xl font-bold text-white mb-6 animate-slide-up">
                    Ruang Meeting
                    <span
                        class="block mt-2 text-transparent bg-clip-text bg-gradient-to-r from-yellow-300 to-orange-300">Premium
                        & Profesional</span>
                </h1>

                <!-- Subtitle -->
                <p class="text-xl text-white/80 max-w-2xl mx-auto mb-10 animate-slide-up animate-delay-100">
                    Solusi ideal untuk rapat, seminar, dan acara bisnis Anda dengan fasilitas lengkap dan pelayanan
                    terbaik.
                </p>

                <!-- CTA Buttons -->
                <div
                    class="flex flex-col sm:flex-row items-center justify-center gap-4 animate-slide-up animate-delay-200">
                    <a href="{{ route('rooms.index') }}"
                        class="w-full sm:w-auto px-8 py-4 bg-white text-primary-600 font-bold rounded-xl shadow-elevated hover:shadow-glow hover:bg-gray-50 transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-search"></i>
                        Lihat Ruang Meeting
                    </a>
                    <a href="#features"
                        class="w-full sm:w-auto px-8 py-4 bg-white/10 backdrop-blur-sm text-white font-semibold rounded-xl border-2 border-white/30 hover:bg-white/20 transition-all duration-300 flex items-center justify-center gap-2">
                        <i class="fas fa-info-circle"></i>
                        Pelajari Lebih Lanjut
                    </a>
                </div>
            </div>
        </div>

        <!-- Wave Divider -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 120" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path
                    d="M0 120L60 105C120 90 240 60 360 45C480 30 600 30 720 37.5C840 45 960 60 1080 67.5C1200 75 1320 75 1380 75L1440 75V120H1380C1320 120 1200 120 1080 120C960 120 840 120 720 120C600 120 480 120 360 120C240 120 120 120 60 120H0Z"
                    fill="#f9fafb" />
            </svg>
        </div>
    </section>

    <!-- Features Section -->
    <section id="features" class="py-20 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-1 bg-primary-100 text-primary-700 rounded-full text-sm font-semibold mb-4">
                    Kenapa Memilih Kami?
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    Fasilitas & Layanan Terbaik
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Kami menyediakan segala yang Anda butuhkan untuk meeting yang sukses
                </p>
            </div>

            <!-- Features Grid -->
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <!-- Feature 1 -->
                <div class="feature-card text-center group">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-primary-500 to-primary-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-soft group-hover:shadow-glow transition-all duration-300 group-hover:scale-110">
                        <i class="fas fa-users text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Kapasitas Fleksibel</h3>
                    <p class="text-gray-600">
                        Ruang meeting dengan kapasitas mulai dari 10 hingga 200 peserta dengan berbagai layout pilihan.
                    </p>
                </div>

                <!-- Feature 2 -->
                <div class="feature-card text-center group">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-secondary-500 to-secondary-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-soft group-hover:shadow-glow transition-all duration-300 group-hover:scale-110">
                        <i class="fas fa-utensils text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Paket Meeting Lengkap</h3>
                    <p class="text-gray-600">
                        Tersedia berbagai paket catering: coffee break, lunch, hingga full board dengan menu pilihan.
                    </p>
                </div>

                <!-- Feature 3 -->
                <div class="feature-card text-center group">
                    <div
                        class="w-16 h-16 bg-gradient-to-br from-accent-500 to-accent-600 rounded-2xl flex items-center justify-center mx-auto mb-6 shadow-soft group-hover:shadow-glow transition-all duration-300 group-hover:scale-110">
                        <i class="fas fa-bolt text-white text-2xl"></i>
                    </div>
                    <h3 class="text-xl font-bold text-gray-800 mb-3">Booking Instan</h3>
                    <p class="text-gray-600">
                        Reservasi cepat tanpa registrasi. Pilih ruangan, tentukan jadwal, dan bayar langsung.
                    </p>
                </div>
            </div>
        </div>
    </section>

    <!-- Promo Section -->
    @if($promotions->count() > 0)
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Section Header -->
            <div class="text-center mb-16">
                <span
                    class="inline-block px-4 py-1 bg-amber-100 text-amber-700 rounded-full text-sm font-semibold mb-4">
                    <i class="fas fa-tags mr-1"></i> Penawaran Terbatas
                </span>
                <h2 class="text-3xl md:text-4xl font-bold text-gray-800 mb-4">
                    Promo Spesial
                </h2>
                <p class="text-gray-600 max-w-2xl mx-auto">
                    Nikmati diskon eksklusif untuk reservasi ruang meeting di Amaze Hotel Kediri
                </p>
            </div>

            <!-- Promo Cards Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach($promotions as $promo)
                    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-xl transition-all duration-500 transform hover:-translate-y-2 animate-fade-in group">
                        <!-- Promo Badge -->
                        <div class="bg-gradient-to-br from-amber-400 via-amber-500 to-orange-500 px-6 py-8 text-center relative overflow-hidden">
                            <div class="absolute -top-6 -right-6 w-24 h-24 bg-white/10 rounded-full"></div>
                            <div class="absolute -bottom-8 -left-8 w-28 h-28 bg-white/10 rounded-full"></div>
                            <div class="absolute top-2 right-3 w-8 h-8 bg-white/10 rounded-full"></div>
                            <p class="text-white/90 text-sm font-medium mb-1 relative z-10">Diskon Hingga</p>
                            <p class="text-white text-6xl font-extrabold relative z-10 drop-shadow-sm">{{ (int) $promo->discount }}%</p>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h3 class="text-lg font-bold text-gray-800 mb-3">{{ $promo->name }}</h3>

                            <div class="flex items-center gap-2 text-green-600 bg-green-50 px-3 py-2 rounded-lg mb-4">
                                <i class="fas fa-check-circle text-sm"></i>
                                <span class="text-sm font-semibold">Promo Aktif</span>
                            </div>

                            <a href="{{ route('rooms.index') }}"
                               class="block w-full text-center bg-gradient-to-r from-amber-500 to-orange-500 hover:from-amber-600 hover:to-orange-600 text-white font-semibold py-3 rounded-xl transition-all duration-300 shadow-sm hover:shadow-md group-hover:shadow-lg">
                                <i class="fas fa-calendar-check mr-2"></i>
                                Reservasi Sekarang
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>
    </section>
    @endif

    <!-- CTA Section -->
    <section class="py-20 bg-white">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="relative bg-gradient-hero rounded-3xl overflow-hidden p-12 md:p-16">
                <!-- Decorative -->
                <div class="floating-decoration w-64 h-64 bg-white/10 -top-10 -right-10"></div>
                <div class="floating-decoration w-48 h-48 bg-secondary-500/20 bottom-0 left-0"></div>

                <div class="relative z-10 text-center">
                    <h2 class="text-3xl md:text-4xl font-bold text-white mb-4">
                        Siap Mengadakan Meeting?
                    </h2>
                    <p class="text-white/80 text-lg max-w-2xl mx-auto mb-8">
                        Pilih ruang meeting sesuai kebutuhan Anda dan nikmati pengalaman meeting terbaik di Kediri.
                    </p>
                    <a href="{{ route('rooms.index') }}"
                        class="inline-flex items-center gap-2 px-8 py-4 bg-white text-primary-600 font-bold rounded-xl shadow-elevated hover:shadow-glow hover:bg-gray-50 transition-all duration-300">
                        <i class="fas fa-arrow-right"></i>
                        Lihat Ruang Meeting
                    </a>
                </div>
            </div>
        </div>
    </section>
</x-app-layout>
