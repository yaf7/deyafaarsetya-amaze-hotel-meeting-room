<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Admin - Amaze Hotel</title>
    @vite(['resources/css/app.css'])
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="min-h-screen flex">
    <!-- Left Side - Branding -->
    <div class="hidden lg:flex lg:w-1/2 bg-gray-900 text-white flex-col justify-center items-center p-12 relative overflow-hidden">
        <!-- Background Pattern -->
        <div class="absolute inset-0 opacity-10">
            <div class="absolute top-20 left-20 w-64 h-64 bg-amber-500 rounded-full blur-3xl"></div>
            <div class="absolute bottom-20 right-20 w-80 h-80 bg-amber-600 rounded-full blur-3xl"></div>
        </div>

        <div class="relative z-10 text-center">
            <img src="{{ asset('storage/rooms/amazelogo.png') }}" alt="Amaze Hotel Logo" class="h-20 w-auto mx-auto mb-8">
            <h1 class="text-4xl font-bold mb-4">Amaze Hotel</h1>
            <p class="text-gray-400 text-lg mb-8">Kediri</p>
            <p class="text-gray-500 max-w-md">
                Sistem manajemen reservasi ruang meeting untuk Amaze Hotel Kediri. Kelola ruangan, paket makanan,menu buffet, promosi, dan transaksi dengan mudah.
            </p>
        </div>
    </div>

    <!-- Right Side - Login Form -->
    <div class="w-full lg:w-1/2 flex items-center justify-center p-8 bg-gray-50">
        <div class="w-full max-w-md">
            <!-- Mobile Logo -->
            <div class="lg:hidden text-center mb-8">
                <img src="{{ asset('storage/rooms/amazelogo.png') }}" alt="Amaze Hotel Logo" class="h-16 w-auto mx-auto mb-4">
                <h1 class="text-2xl font-bold text-gray-800">Amaze Hotel</h1>
                <p class="text-gray-500">Kediri</p>
            </div>

            <!-- Login Card -->
            <div class="bg-white rounded-2xl shadow-lg p-8">
                <div class="text-center mb-8">
                    <h2 class="text-2xl font-bold text-gray-800">Selamat Datang</h2>
                    <p class="text-gray-500 mt-2">Login ke Admin Panel</p>
                </div>

                @if ($errors->any())
                    <div class="bg-red-50 border border-red-200 text-red-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
                        <i class="fas fa-exclamation-circle"></i>
                        <span>{{ $errors->first() }}</span>
                    </div>
                @endif

                <form method="POST" action="{{ route('admin.login') }}">
                    @csrf
                    <div class="mb-5">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-user text-gray-400 mr-2"></i>Username
                        </label>
                        <input type="text" name="username"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 outline-none transition-colors"
                               placeholder="Masukkan username"
                               required>
                    </div>

                    <div class="mb-6">
                        <label class="block text-sm font-medium text-gray-700 mb-2">
                            <i class="fas fa-lock text-gray-400 mr-2"></i>Password
                        </label>
                        <input type="password" name="password"
                               class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 outline-none transition-colors"
                               placeholder="Masukkan password"
                               required>
                    </div>

                    <button type="submit"
                            class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3.5 rounded-xl transition-all duration-200 flex items-center justify-center gap-2 shadow-lg shadow-amber-500/30">
                        <i class="fas fa-sign-in-alt"></i>
                        <span>Login</span>
                    </button>
                </form>
            </div>

            <!-- Footer -->
            <p class="text-center text-gray-400 text-sm mt-8">
                © DEYAFA ARSETYA
            </p>
        </div>
    </div>
</body>
</html>
