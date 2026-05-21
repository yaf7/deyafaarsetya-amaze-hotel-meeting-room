<!DOCTYPE html>
<html lang="id">

<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Amaze Hotel Kediri - Meeting Room</title>
    @vite(['resources/css/app.css', 'resources/js/app.js'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Playfair+Display:wght@500;600;700&family=Inter:wght@300;400;500;600&display=swap"
        rel="stylesheet">
    <style>
        .font-display {
            font-family: 'Playfair Display', serif;
        }
    </style>
    <!-- AlpineJS for interactive dropdowns -->
    <script defer src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
</head>

<body class="bg-gray-50 font-sans">
    <div class="min-h-screen flex flex-col">

        <!-- Navbar Clean & Elegant untuk Hotel -->
        <nav class="sticky top-0 z-50 bg-white border-b border-gray-100">
            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
                <div class="flex justify-between items-center h-20">

                    <!-- Logo - Elegant Hotel Style -->
                    <a href="/" class="flex items-center gap-3">
                        <img src="{{ asset('storage/rooms/amazelogo.png') }}" alt="Amaze Hotel Logo"
                            class="h-14 w-auto">
                        <div class="flex flex-col">
                            <span class="font-display text-xl font-semibold text-gray-900 tracking-wide">AMAZE</span>
                            <span class="text-[10px] text-gray-500 tracking-[0.25em] uppercase">Hotel Kediri</span>
                        </div>
                    </a>

                    <!-- Desktop Navigation - Clean & Minimal -->
                    <div class="hidden md:flex items-center gap-8">
                        <a href="/"
                            class="text-gray-600 hover:text-amber-600 font-medium text-sm tracking-wide transition-colors">
                            Beranda
                        </a>
                        <a href="/rooms"
                            class="text-gray-600 hover:text-amber-600 font-medium text-sm tracking-wide transition-colors">
                            Meeting Room
                        </a>

                        <a href="/contact"
                            class="text-gray-600 hover:text-amber-600 font-medium text-sm tracking-wide transition-colors">
                            Kontak
                        </a>
                    </div>

                    <!-- CTA & Auth Button - Elegant -->
                    <div class="hidden md:flex items-center gap-4">
                        @if(auth('customer')->check())
                            @php 
                                $user = auth('customer')->user(); 
                                $unreadRescheduleCount = $user->reservations()
                                    ->whereIn('reschedule_status', ['approved', 'rejected'])
                                    ->where('reschedule_notification_read', false)
                                    ->count();
                            @endphp
                            <!-- Account Dropdown -->
                            <div class="relative" x-data="{ open: false }">
                                <button @click="open = !open" @click.outside="open = false" 
                                    class="relative inline-flex items-center gap-2 px-3 py-1.5 bg-white border border-gray-200 rounded-full hover:bg-gray-50 hover:border-amber-500 transition-all shadow-sm">
                                    @if($unreadRescheduleCount > 0)
                                        <span class="absolute -top-1 -right-1 flex h-3 w-3">
                                            <span class="animate-ping absolute inline-flex h-full w-full rounded-full bg-rose-400 opacity-75"></span>
                                            <span class="relative inline-flex rounded-full h-3 w-3 bg-rose-500"></span>
                                        </span>
                                    @endif
                                    @if($user->photo)
                                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Avatar" class="w-7 h-7 rounded-full object-cover border border-amber-100">
                                    @else
                                        <div class="w-7 h-7 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold text-xs">
                                            {{ strtoupper(substr($user->name, 0, 1)) }}
                                        </div>
                                    @endif
                                    <span class="text-sm font-semibold text-gray-700 max-w-[120px] truncate">{{ $user->name }}</span>
                                    <i class="fas fa-chevron-down text-xs text-gray-400 transition-transform duration-200" :class="open ? 'rotate-180' : ''"></i>
                                </button>

                                <!-- Dropdown Menu -->
                                <div x-show="open" 
                                    x-transition:enter="transition ease-out duration-100"
                                    x-transition:enter-start="transform opacity-0 scale-95"
                                    x-transition:enter-end="transform opacity-100 scale-100"
                                    x-transition:leave="transition ease-in duration-75"
                                    x-transition:leave-start="transform opacity-100 scale-100"
                                    x-transition:leave-end="transform opacity-0 scale-95"
                                    class="absolute right-0 mt-2 w-48 bg-white rounded-2xl shadow-xl border border-gray-100 py-2 z-50"
                                    style="display: none;">
                                    <div class="px-4 py-2 border-b border-gray-50">
                                        <p class="text-xs text-gray-400">Selamat datang,</p>
                                        <p class="text-sm font-bold text-gray-800 truncate">{{ $user->name }}</p>
                                    </div>
                                    <a href="{{ route('customer.profile') }}" class="flex items-center justify-between px-4 py-2.5 text-sm text-gray-700 hover:bg-amber-50 hover:text-amber-700 transition-colors">
                                        <div class="flex items-center gap-2">
                                            <i class="fas fa-user text-xs w-4"></i>
                                            <span>Profil Saya</span>
                                        </div>
                                        @if($unreadRescheduleCount > 0)
                                            <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">
                                                {{ $unreadRescheduleCount }}
                                            </span>
                                        @endif
                                    </a>
                                    <form action="{{ route('customer.logout') }}" method="POST" class="block m-0">
                                        @csrf
                                        <button type="submit" class="w-full flex items-center gap-2 px-4 py-2.5 text-sm text-red-600 hover:bg-red-50 transition-colors text-left border-0 bg-transparent cursor-pointer">
                                            <i class="fas fa-sign-out-alt text-xs w-4"></i>
                                            <span>Keluar Akun</span>
                                        </button>
                                    </form>
                                </div>
                            </div>
                        @else
                            <a href="{{ route('login') }}" class="text-sm font-semibold text-gray-600 hover:text-amber-600 transition-colors">Masuk</a>
                            <a href="{{ route('customer.register') }}" class="px-4 py-2 bg-amber-50 border border-amber-200 text-amber-700 text-sm font-semibold rounded-full hover:bg-amber-100 transition-all">Daftar</a>
                        @endif

                        <a href="/rooms"
                            class="inline-flex items-center gap-2 px-6 py-2.5 bg-amber-500 text-white font-semibold text-sm rounded-full hover:bg-amber-600 transition-all duration-300 shadow-sm hover:shadow-md">
                            <span>Reservasi</span>
                            <i class="fas fa-arrow-right text-xs"></i>
                        </a>
                    </div>

                    <!-- Mobile Menu Button -->
                    <button class="md:hidden p-2.5 rounded-full text-gray-600 hover:bg-gray-100 transition-colors"
                        type="button" aria-label="Toggle menu"
                        onclick="var menu = document.getElementById('mobile-menu'); menu.classList.toggle('hidden'); this.querySelector('.fa-bars').classList.toggle('hidden'); this.querySelector('.fa-times').classList.toggle('hidden');">
                        <i class="fas fa-bars text-lg"></i>
                        <i class="fas fa-times text-lg hidden"></i>
                    </button>
                </div>
            </div>

            <!-- Mobile Menu - Clean -->
            <div class="md:hidden hidden bg-white border-t border-gray-100" id="mobile-menu">
                <div class="px-4 py-4 space-y-1">
                    @if(auth('customer')->check())
                        @php 
                            $user = auth('customer')->user(); 
                            $unreadRescheduleCount = $user->reservations()
                                ->whereIn('reschedule_status', ['approved', 'rejected'])
                                ->where('reschedule_notification_read', false)
                                ->count();
                        @endphp
                        <div class="flex items-center gap-3 px-4 py-3 border-b border-gray-100 mb-2">
                            @if($user->photo)
                                <img src="{{ asset('storage/' . $user->photo) }}" alt="Avatar" class="w-10 h-10 rounded-full object-cover border border-amber-100">
                            @else
                                <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center text-amber-600 font-bold">
                                    {{ strtoupper(substr($user->name, 0, 1)) }}
                                </div>
                            @endif
                            <div>
                                <p class="text-sm font-bold text-gray-800">{{ $user->name }}</p>
                                <p class="text-xs text-gray-500">{{ $user->email }}</p>
                            </div>
                        </div>
                        <a href="{{ route('customer.profile') }}"
                            class="flex items-center justify-between px-4 py-3 text-gray-700 hover:text-amber-600 hover:bg-amber-50 rounded-lg font-medium transition-colors">
                            <div class="flex items-center">
                                <i class="fas fa-user-circle mr-2 text-gray-400"></i> Profil Saya
                            </div>
                            @if($unreadRescheduleCount > 0)
                                <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">
                                    {{ $unreadRescheduleCount }}
                                </span>
                            @endif
                        </a>
                    @else
                        <div class="grid grid-cols-2 gap-2 px-4 py-2 border-b border-gray-100 mb-2">
                            <a href="{{ route('login') }}" class="text-center py-2 bg-gray-50 hover:bg-gray-100 text-gray-700 text-sm font-semibold rounded-lg transition-colors">Masuk</a>
                            <a href="{{ route('customer.register') }}" class="text-center py-2 bg-amber-500 hover:bg-amber-600 text-white text-sm font-semibold rounded-lg transition-colors">Daftar</a>
                        </div>
                    @endif

                    <a href="/"
                        class="block px-4 py-3 text-gray-700 hover:text-amber-600 hover:bg-amber-50 rounded-lg font-medium transition-colors">
                        Beranda
                    </a>
                    <a href="/rooms"
                        class="block px-4 py-3 text-gray-700 hover:text-amber-600 hover:bg-amber-50 rounded-lg font-medium transition-colors">
                        Meeting Room
                    </a>

                    <a href="/contact"
                        class="block px-4 py-3 text-gray-700 hover:text-amber-600 hover:bg-amber-50 rounded-lg font-medium transition-colors">
                        Kontak
                    </a>

                    @if(auth('customer')->check())
                        <form action="{{ route('customer.logout') }}" method="POST" class="block pt-2 m-0">
                            @csrf
                            <button type="submit" class="w-full text-left px-4 py-3 text-red-600 hover:bg-red-50 rounded-lg font-medium transition-colors border-0 bg-transparent cursor-pointer">
                                <i class="fas fa-sign-out-alt mr-2 text-red-400"></i> Keluar Akun
                            </button>
                        </form>
                    @endif

                    <div class="pt-3 px-4">
                        <a href="/rooms"
                            class="block w-full text-center py-3 bg-amber-500 text-white font-medium rounded-full hover:bg-amber-600 transition-colors">
                            Reservasi Sekarang
                        </a>
                    </div>
                </div>
            </div>
        </nav>

        <!-- Main Content -->
        <main class="flex-grow">
            {{ $slot }}
        </main>

        <!-- Footer Modern & Elegant -->
        <footer class="bg-gray-900 text-white">
            <!-- Top Accent Line -->
            <div class="h-1 bg-gradient-to-r from-amber-400 via-amber-500 to-amber-600"></div>

            <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-16">
                <div class="grid grid-cols-1 md:grid-cols-12 gap-10">

                    <!-- Brand Section -->
                    <div class="md:col-span-5">
                        <div class="flex items-center gap-3 mb-6">
                            <img src="{{ asset('storage/rooms/amazelogo.png') }}" alt="Amaze Hotel Logo"
                                class="h-16 w-auto">
                            <div>
                                <h3 class="font-display text-2xl font-semibold">AMAZE</h3>
                                <p class="text-gray-500 text-xs tracking-[0.2em] uppercase">Hotel Kediri</p>
                            </div>
                        </div>
                        <p class="text-gray-400 text-sm leading-relaxed max-w-sm mb-6">
                            Pengalaman meeting premium dengan fasilitas terbaik di Kediri. Ruang meeting modern, paket
                            makanan, dan layanan profesional.
                        </p>

                        <!-- Social Icons -->
                        <div class="flex items-center gap-3">
                            <a href="https://www.instagram.com/amazehotelkediri?igsh=aGN0NnM0YTBhampu"
                                class="w-10 h-10 border border-gray-700 rounded-full flex items-center justify-center text-gray-400 hover:border-amber-500 hover:text-amber-500 transition-all duration-300">
                                <i class="fab fa-instagram text-sm"></i>
                            </a>
                            <a href="https://wa.me/6281333753065" target="_blank"
                                class="w-10 h-10 border border-gray-700 rounded-full flex items-center justify-center text-gray-400 hover:border-amber-500 hover:text-amber-500 transition-all duration-300">
                                <i class="fab fa-whatsapp text-sm"></i>
                            </a>
                        </div>
                    </div>

                    <!-- Quick Links -->
                    <div class="md:col-span-3">
                        <h4 class="text-white font-semibold mb-6 text-sm uppercase tracking-wider">Menu</h4>
                        <ul class="space-y-3">
                            <li><a href="/"
                                    class="text-gray-400 hover:text-amber-500 text-sm transition-colors">Beranda</a>
                            </li>
                            <li><a href="/rooms"
                                    class="text-gray-400 hover:text-amber-500 text-sm transition-colors">Meeting
                                    Room</a></li>

                            <li><a href="/contact"
                                    class="text-gray-400 hover:text-amber-500 text-sm transition-colors">Hubungi
                                    Kami</a></li>
                        </ul>
                    </div>

                    <!-- Contact Info -->
                    <div class="md:col-span-4">
                        <h4 class="text-white font-semibold mb-6 text-sm uppercase tracking-wider">Kontak</h4>
                        <ul class="space-y-4">
                            <li class="flex items-start gap-3">
                                <i class="fas fa-map-marker-alt text-amber-500 mt-1"></i>
                                <a href="https://maps.app.goo.gl/D6EBq8XXiza2keoQ8" target="_blank"
                                    class="text-gray-400 hover:text-amber-500 text-sm transition-colors">
                                    Jl. Raden Ajeng Kartini No.69, Doko,<br>Kec. Ngasem, Kabupaten Kediri,<br>Jawa Timur
                                    64182
                                </a>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fab fa-whatsapp text-amber-500"></i>
                                <a href="https://wa.me/6281333753065" target="_blank"
                                    class="text-gray-400 hover:text-amber-500 text-sm transition-colors">0813-3375-3065</a>
                            </li>
                            <li class="flex items-center gap-3">
                                <i class="fas fa-envelope text-amber-500"></i>
                                <span class="text-gray-400 text-sm">amazehotelkediri@gmail.com</span>
                            </li>
                        </ul>
                    </div>
                </div>

                <!-- Bottom Bar -->
                <div class="border-t border-gray-800 mt-12 pt-8 text-center">
                    <p class="text-gray-500 text-sm">
                        DEYAFA ARSETYA
                    </p>
                </div>
            </div>
        </footer>
    </div>

    @stack('scripts')
</body>

</html>
