<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin - Amaze Hotel</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <style>
        body { font-family: 'Inter', sans-serif; }

        /* Sidebar transition */
        #desktop-sidebar {
            transition: width 0.3s ease;
        }
        #desktop-sidebar .sidebar-text {
            transition: opacity 0.2s ease, width 0.2s ease;
            white-space: nowrap;
            overflow: hidden;
        }
        #desktop-sidebar.collapsed {
            width: 5rem !important;
        }
        #desktop-sidebar.collapsed .sidebar-text {
            opacity: 0;
            width: 0;
            margin: 0;
            padding: 0;
        }
        #desktop-sidebar.collapsed .sidebar-logo-text {
            display: none;
        }
        #desktop-sidebar.collapsed .sidebar-label {
            display: none;
        }
        #desktop-sidebar.collapsed nav a,
        #desktop-sidebar.collapsed .sidebar-logout-btn {
            justify-content: center;
            padding-left: 0;
            padding-right: 0;
        }
        #desktop-sidebar.collapsed nav a i,
        #desktop-sidebar.collapsed .sidebar-logout-btn i {
            width: auto;
            font-size: 1.1rem;
        }
        #desktop-sidebar.collapsed .sidebar-logo-section {
            justify-content: center;
            padding: 1rem;
        }
        #desktop-sidebar.collapsed .sidebar-logo-section img {
            height: 2rem;
        }
        #desktop-sidebar.collapsed .sidebar-bottom {
            width: 5rem;
        }
        #desktop-sidebar.collapsed .toggle-btn {
            justify-content: center;
        }
        #desktop-sidebar.collapsed .toggle-btn i {
            transform: rotate(180deg);
        }
        .toggle-btn i {
            transition: transform 0.3s ease;
        }

        /* Main content transition */
        #main-content {
            transition: margin-left 0.3s ease;
        }

        /* Tooltip for collapsed sidebar */
        #desktop-sidebar.collapsed nav a {
            position: relative;
        }
        #desktop-sidebar.collapsed nav a:hover::after {
            content: attr(data-tooltip);
            position: absolute;
            left: 100%;
            top: 50%;
            transform: translateY(-50%);
            background: #1f2937;
            color: white;
            padding: 0.4rem 0.75rem;
            border-radius: 0.5rem;
            font-size: 0.75rem;
            white-space: nowrap;
            z-index: 100;
            margin-left: 0.5rem;
            box-shadow: 0 4px 6px -1px rgba(0,0,0,0.1);
        }
    </style>
</head>
<body class="bg-gray-100">
    <div class="flex min-h-screen">
        
        <!-- Sidebar Modern (Desktop) -->
        <aside id="desktop-sidebar" class="w-64 bg-gray-900 text-white flex-shrink-0 hidden lg:flex lg:flex-col sticky top-0 h-screen overflow-y-auto">
            <!-- Logo Section -->
            <div class="p-6 border-b border-gray-800 sidebar-logo-section flex items-center gap-3">
                <img src="{{ asset('storage/rooms/amazelogo.png') }}" alt="Amaze Hotel Logo" class="h-10 w-auto">
                <div class="sidebar-logo-text flex-1">
                    <h1 class="font-bold text-lg">Amaze Hotel</h1>
                    <p class="text-xs text-gray-500">Admin Panel</p>
                </div>
                <button onclick="toggleDesktopSidebar()" class="toggle-btn text-gray-500 hover:text-white transition-colors p-1">
                    <i class="fas fa-chevron-left"></i>
                </button>
            </div>
            
            <!-- Navigation -->
            <nav class="p-4 space-y-1 flex-1">
                <p class="text-xs text-gray-500 uppercase tracking-wider px-4 mb-3 sidebar-label">Menu Utama</p>
                
                <a href="{{ route('admin.dashboard') }}" data-tooltip="Dashboard"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-home w-5 text-center"></i>
                    <span class="sidebar-text">Dashboard</span>
                </a>

                <a href="{{ route('admin.reservations') }}" data-tooltip="Semua Reservasi"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reservations') || request()->routeIs('admin.reservation.show') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-calendar-check w-5 text-center"></i>
                    <span class="sidebar-text">Semua Reservasi</span>
                    @php
                        $pendingRescheduleCount = \App\Models\Reservation::where('reschedule_status', 'pending')->count();
                    @endphp
                    @if($pendingRescheduleCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full sidebar-text animate-pulse">
                            {{ $pendingRescheduleCount }}
                        </span>
                    @endif
                </a>

                <a href="{{ route('admin.rooms.index') }}" data-tooltip="Kelola Ruang"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.rooms.*') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-door-open w-5 text-center"></i>
                    <span class="sidebar-text">Kelola Ruang</span>
                </a>

                <a href="{{ route('admin.packages.index') }}" data-tooltip="Kelola Paket"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.packages.*') && !request()->routeIs('admin.buffet-menus.*') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-utensils w-5 text-center"></i>
                    <span class="sidebar-text">Kelola Paket</span>
                </a>

                <a href="{{ route('admin.buffet-menus.index') }}" data-tooltip="Kelola Menu Buffet"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.buffet-menus.*') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-bowl-food w-5 text-center"></i>
                    <span class="sidebar-text">Kelola Menu Buffet</span>
                </a>

                <a href="{{ route('admin.promotions.index') }}" data-tooltip="Kelola Promosi"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.promotions.*') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-tags w-5 text-center"></i>
                    <span class="sidebar-text">Kelola Promosi</span>
                </a>

                <a href="{{ route('admin.reports') }}" data-tooltip="Laporan"
                   class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all duration-200 {{ request()->routeIs('admin.reports') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800 hover:text-white' }}">
                    <i class="fas fa-chart-bar w-5 text-center"></i>
                    <span class="sidebar-text">Laporan</span>
                </a>
            </nav>



            <!-- Logout -->
            <div class="p-4 border-t border-gray-800 sidebar-bottom">
                <form method="POST" action="{{ route('admin.logout') }}">
                    @csrf
                    <button type="submit" class="sidebar-logout-btn flex items-center gap-3 px-4 py-3 rounded-xl text-gray-400 hover:bg-red-500/10 hover:text-red-400 transition-all duration-200 w-full">
                        <i class="fas fa-sign-out-alt w-5 text-center"></i>
                        <span class="sidebar-text">Logout</span>
                    </button>
                </form>
            </div>
        </aside>

        <!-- Mobile Sidebar Overlay -->
        <div id="mobile-overlay" class="fixed inset-0 bg-black/50 z-40 hidden lg:hidden" onclick="toggleMobileSidebar()"></div>
        
        <!-- Mobile Sidebar -->
        <aside id="mobile-sidebar" class="fixed left-0 top-0 bottom-0 w-64 bg-gray-900 text-white z-50 transform -translate-x-full transition-transform duration-300 lg:hidden">
            <!-- Same content as desktop sidebar -->
            <div class="p-6 border-b border-gray-800 flex justify-between items-center">
                <div class="flex items-center gap-3">
                    <img src="{{ asset('storage/rooms/amazelogo.png') }}" alt="Amaze Hotel Logo" class="h-10 w-auto">
                    <div>
                        <h1 class="font-bold text-lg">Amaze Hotel</h1>
                        <p class="text-xs text-gray-500">Admin Panel</p>
                    </div>
                </div>
                <button onclick="toggleMobileSidebar()" class="text-gray-400 hover:text-white">
                    <i class="fas fa-times text-xl"></i>
                </button>
            </div>
            
            <nav class="p-4 space-y-1">
                <a href="{{ route('admin.dashboard') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.dashboard') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800' }}">
                    <i class="fas fa-home w-5"></i><span>Dashboard</span>
                </a>
                <a href="{{ route('admin.reservations') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.reservations') || request()->routeIs('admin.reservation.show') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800' }}">
                    <i class="fas fa-calendar-check w-5"></i><span>Semua Reservasi</span>
                    @if($pendingRescheduleCount > 0)
                        <span class="ml-auto bg-red-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full">
                            {{ $pendingRescheduleCount }}
                        </span>
                    @endif
                </a>
                <a href="{{ route('admin.rooms.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.rooms.*') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800' }}">
                    <i class="fas fa-door-open w-5"></i><span>Kelola Ruang</span>
                </a>
                <a href="{{ route('admin.packages.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.packages.*') && !request()->routeIs('admin.buffet-menus.*') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800' }}">
                    <i class="fas fa-utensils w-5"></i><span>Kelola Paket</span>
                </a>
                <a href="{{ route('admin.buffet-menus.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.buffet-menus.*') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800' }}">
                    <i class="fas fa-bowl-food w-5"></i><span>Kelola Menu Buffet</span>
                </a>
                <a href="{{ route('admin.promotions.index') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.promotions.*') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800' }}">
                    <i class="fas fa-tags w-5"></i><span>Kelola Promosi</span>
                </a>
                <a href="{{ route('admin.reports') }}" class="flex items-center gap-3 px-4 py-3 rounded-xl transition-all {{ request()->routeIs('admin.reports') ? 'bg-amber-500 text-white' : 'text-gray-400 hover:bg-gray-800' }}">
                    <i class="fas fa-chart-bar w-5"></i><span>Laporan</span>
                </a>
            </nav>
        </aside>

        <!-- Main Content -->
        <div id="main-content" class="flex-1 flex flex-col min-h-screen">
            <!-- Top Header -->
            <header class="bg-white border-b border-gray-200 sticky top-0 z-30">
                <div class="flex items-center justify-between px-4 sm:px-6 py-4">
                    <!-- Mobile Menu Button -->
                    <button onclick="toggleMobileSidebar()" class="lg:hidden p-2 rounded-lg text-gray-600 hover:bg-gray-100">
                        <i class="fas fa-bars text-xl"></i>
                    </button>
                    
                    <!-- Page Title -->
                    <div class="hidden lg:block">
                        <h2 class="text-lg font-semibold text-gray-800">@yield('page-title', 'Dashboard')</h2>
                    </div>
                    
                    <!-- User Info -->
                    <div class="flex items-center gap-4">
                        <div class="text-right hidden sm:block">
                            <p class="text-sm font-medium text-gray-700">{{ Auth::user()->name }}</p>
                            <p class="text-xs text-gray-500">Administrator</p>
                        </div>
                        <div class="w-10 h-10 bg-amber-100 rounded-full flex items-center justify-center">
                            <i class="fas fa-user text-amber-600"></i>
                        </div>
                    </div>
                </div>
            </header>

            <!-- Page Content -->
            <main class="flex-1 p-4 sm:p-6 lg:p-8 overflow-auto">
                @yield('content')
            </main>
        </div>
    </div>

    <script>
        function toggleMobileSidebar() {
            const sidebar = document.getElementById('mobile-sidebar');
            const overlay = document.getElementById('mobile-overlay');
            sidebar.classList.toggle('-translate-x-full');
            overlay.classList.toggle('hidden');
        }

        function toggleDesktopSidebar() {
            const sidebar = document.getElementById('desktop-sidebar');
            sidebar.classList.toggle('collapsed');
            
            // Save state to localStorage
            const isCollapsed = sidebar.classList.contains('collapsed');
            localStorage.setItem('sidebar-collapsed', isCollapsed);
        }

        // Restore sidebar state on page load
        document.addEventListener('DOMContentLoaded', function() {
            const isCollapsed = localStorage.getItem('sidebar-collapsed') === 'true';
            if (isCollapsed) {
                document.getElementById('desktop-sidebar').classList.add('collapsed');
            }
        });
    </script>
</body>
</html>
