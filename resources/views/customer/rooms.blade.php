<x-app-layout>
    <!-- Header Section -->
    <section class="bg-gradient-hero py-16 relative overflow-hidden">
        <div class="floating-decoration w-64 h-64 bg-white/10 -top-10 -right-10"></div>
        <div class="floating-decoration w-48 h-48 bg-secondary-500/20 bottom-0 left-10"></div>
        
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 relative z-10">
            <div class="text-center">
                <h1 class="text-4xl md:text-5xl font-bold text-white mb-4">Ruang Meeting Kami</h1>
                <p class="text-white/80 text-lg max-w-2xl mx-auto">
                    Pilih ruangan yang sesuai dengan kebutuhan meeting Anda
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

    <!-- Rooms Grid -->
    <section class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Info -->
            <div class="flex items-center justify-between mb-10">
                <div>
                    <p class="text-gray-600">Menampilkan <span class="font-semibold text-primary-600">{{ count($rooms) }}</span> ruang meeting</p>
                </div>
            </div>

            <!-- Rooms Grid -->
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-8">
                @foreach ($rooms as $room)
                    @php
                        $layout = $room->layout;
                    @endphp
                    <div class="room-card animate-fade-in">
                        <!-- Image Container -->
                        <div class="relative overflow-hidden">
                            <img src="{{ $room->photo ? asset('storage/' . $room->photo) : 'https://images.unsplash.com/photo-1517502884422-41eaead166d4?w=600&h=400&fit=crop' }}"
                                 alt="{{ $room->name }}"
                                 class="room-image">
                            <!-- Overlay on hover -->
                            <div class="absolute inset-0 bg-gradient-to-t from-black/60 via-transparent to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300"></div>
                            <!-- Badge -->
                            <div class="absolute top-4 left-4">
                                <span class="badge badge-info">
                                    <i class="fas fa-star mr-1"></i> Premium
                                </span>
                            </div>
                        </div>

                        <!-- Content -->
                        <div class="p-6">
                            <h2 class="text-xl font-bold text-gray-800 mb-2">{{ $room->name }}</h2>
                            <p class="text-gray-600 text-sm mb-4 line-clamp-2">{{ $room->facilities }}</p>

                            <!-- Capacity Info -->
                            <div class="bg-gray-50 rounded-xl p-4 mb-5">
                                <p class="text-sm font-semibold text-gray-700 mb-3 flex items-center gap-2">
                                    <i class="fas fa-chair text-primary-500"></i>
                                    Kapasitas Layout
                                </p>
                                <div class="grid grid-cols-2 gap-2 text-sm">
                                    <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2">
                                        <span class="text-gray-500">Theater</span>
                                        <span class="font-semibold text-gray-700">{{ $layout['theater'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2">
                                        <span class="text-gray-500">Classroom</span>
                                        <span class="font-semibold text-gray-700">{{ $layout['classroom'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2">
                                        <span class="text-gray-500">Round Table</span>
                                        <span class="font-semibold text-gray-700">{{ $layout['round_table'] ?? 0 }}</span>
                                    </div>
                                    <div class="flex items-center justify-between bg-white rounded-lg px-3 py-2">
                                        <span class="text-gray-500">U-Shape</span>
                                        <span class="font-semibold text-gray-700">{{ $layout['u_shape'] ?? 0 }}</span>
                                    </div>
                                </div>
                            </div>

                            <!-- CTA Button -->
                            <a href="{{ route('reservation.form', $room->id) }}"
                               class="w-full btn-primary flex items-center justify-center gap-2">
                                <i class="fas fa-calendar-check"></i>
                                Pilih Ruangan Ini
                            </a>
                        </div>
                    </div>
                @endforeach
            </div>

            @if(count($rooms) === 0)
                <div class="text-center py-20">
                    <div class="w-24 h-24 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-6">
                        <i class="fas fa-door-closed text-gray-400 text-4xl"></i>
                    </div>
                    <h3 class="text-xl font-semibold text-gray-700 mb-2">Belum Ada Ruangan</h3>
                    <p class="text-gray-500">Ruang meeting sedang dalam persiapan</p>
                </div>
            @endif
        </div>
    </section>
</x-app-layout>
