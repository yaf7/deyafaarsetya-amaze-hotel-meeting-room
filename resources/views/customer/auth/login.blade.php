<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Pelanggan - Amaze Hotel</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <link href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.0.0/css/all.min.css" rel="stylesheet">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&display=swap" rel="stylesheet">
    <style>
        body { font-family: 'Inter', sans-serif; }
    </style>
</head>
<body class="bg-gray-50 flex items-center justify-center min-h-screen p-4">
    <div class="bg-white rounded-2xl shadow-xl w-full max-w-md overflow-hidden">
        <div class="pt-8 px-6 text-center">
            <img src="{{ asset('storage/rooms/amazelogo.png') }}" alt="Amaze Hotel Logo" class="h-20 w-auto mx-auto mb-4 object-contain">
            <h1 class="text-2xl font-bold text-gray-900 mb-2">Selamat Datang Kembali!</h1>
            <p class="text-gray-500 text-sm">Silakan masuk untuk melanjutkan proses pemesanan dan mengelola profil Anda di Amaze Hotel.</p>
        </div>
        
        <div class="p-6 sm:p-8">
            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 text-sm border border-red-100">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <form action="{{ route('customer.login.submit') }}" method="POST" class="space-y-5">
                @csrf
                <div>
                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email</label>
                    <input type="email" id="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                </div>

                <div>
                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi</label>
                    <input type="password" id="password" name="password" required
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                </div>

                <button type="submit" class="w-full bg-amber-500 hover:bg-amber-600 text-white font-semibold py-3 rounded-xl transition-colors shadow-md hover:shadow-lg">
                    Masuk ke Akun
                </button>
            </form>

            <div class="mt-6 text-center text-sm text-gray-600">
                Belum punya akun? <a href="{{ route('customer.register') }}" class="text-amber-600 hover:text-amber-700 hover:underline font-semibold transition-colors">Daftar di sini</a>
            </div>
            <div class="mt-4 text-center text-sm">
                <a href="{{ route('home') }}" class="text-gray-500 hover:text-gray-700"><i class="fas fa-arrow-left mr-1"></i> Kembali ke Beranda</a>
            </div>
        </div>
    </div>
</body>
</html>
