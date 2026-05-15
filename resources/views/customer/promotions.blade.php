<x-app-layout>
    <!-- Header Section with Blue Gradient -->
    <section class="bg-gradient-hero py-16 relative overflow-hidden">
        <div class="floating-decoration w-64 h-64 bg-white/10 -top-10 -right-10"></div>
        <div class="floating-decoration w-48 h-48 bg-secondary-500/20 bottom-0 left-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Promo Spesial</h1>
                <p class="text-white/80 text-lg max-w-2xl mx-auto">
                    Nikmati diskon eksklusif untuk reservasi ruang meeting di Amaze Hotel Kediri
                </p>
            </div>
        </div>
        
        <!-- Wave -->
        <div class="absolute bottom-0 left-0 right-0">
            <svg viewBox="0 0 1440 60" fill="none" xmlns="http://www.w3.org/2000/svg">
                <path d="M0 60L60 50C120 40 240 20 360 15C480 10 600 20 720 25C840 30 960 30 1080 25C1200 20 1320 10 1380 5L1440 0V60H0Z" fill="#f9fafb"/>
            </svg>
        </div>
    </section>

    <section class="py-16 bg-gray-50">
        <div class="max-w-5xl mx-auto px-4 sm:px-6 lg:px-8">
            @if($promotions->count() > 0)
                <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
                    @foreach($promotions as $promo)
                        <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-lg transition-all duration-300 transform hover:-translate-y-1 animate-fade-in">
                            <!-- Promo Badge -->
                            <div class="bg-gradient-to-r from-amber-500 to-amber-600 px-6 py-5 text-center relative overflow-hidden">
                                <div class="absolute -top-4 -right-4 w-20 h-20 bg-white/10 rounded-full"></div>
                                <div class="absolute -bottom-6 -left-6 w-24 h-24 bg-white/10 rounded-full"></div>
                                <p class="text-white/80 text-sm font-medium mb-1">Diskon Hingga</p>
                                <p class="text-white text-5xl font-bold">{{ (int) $promo->discount }}%</p>
                            </div>

                            <!-- Content -->
                            <div class="p-6">
                                <h3 class="text-lg font-bold text-gray-800 mb-3">{{ $promo->name }}</h3>

                                <div class="flex items-center gap-2 text-green-600 bg-green-50 px-3 py-2 rounded-lg mb-4">
                                    <i class="fas fa-check-circle text-sm"></i>
                                    <span class="text-sm font-semibold">Promo Aktif</span>
                                </div>

                                <a href="{{ route('rooms.index') }}" 
                                   class="block w-full text-center bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-xl transition-colors">
                                    <i class="fas fa-calendar-check mr-2"></i>
                                    Reservasi Sekarang
                                </a>
                            </div>
                        </div>
                    @endforeach
                </div>
            @else
                <!-- Empty State -->
                <div class="text-center py-16">
                    <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-tag text-gray-400 text-3xl"></i>
                    </div>
                    <h2 class="text-2xl font-bold text-gray-700 mb-3">Belum Ada Promo</h2>
                    <p class="text-gray-500 mb-6">Promo spesial akan ditampilkan di sini. Pantau terus halaman ini!</p>
                    <a href="{{ route('rooms.index') }}" class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white font-semibold px-6 py-3 rounded-xl transition-colors">
                        <i class="fas fa-door-open"></i>
                        Lihat Meeting Room
                    </a>
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
