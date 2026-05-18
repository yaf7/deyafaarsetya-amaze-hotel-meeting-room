<x-app-layout>
    <div class="bg-gray-50 min-h-screen py-12">
        <div class="max-w-6xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Header -->
            <div class="flex items-center justify-between mb-8">
                <div>
                    <h1 class="text-3xl font-bold text-gray-900">Akun Saya</h1>
                    <p class="text-sm text-gray-500 mt-1">Kelola data diri dan lihat riwayat pemesanan Anda.</p>
                </div>
                <a href="{{ route('home') }}" class="text-sm font-medium text-gray-500 hover:text-gray-700 bg-white border border-gray-200 px-4 py-2 rounded-lg transition-colors">
                    <i class="fas fa-arrow-left mr-2"></i>Kembali
                </a>
            </div>

            @if(session('success'))
                <div class="bg-green-50 text-green-700 p-4 rounded-xl mb-6 border border-green-100 flex items-center gap-3">
                    <i class="fas fa-check-circle"></i>
                    <p class="font-medium text-sm">{{ session('success') }}</p>
                </div>
            @endif

            @if ($errors->any())
                <div class="bg-red-50 text-red-600 p-4 rounded-xl mb-6 border border-red-100 text-sm">
                    <ul class="list-disc pl-4 space-y-1">
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="bg-white rounded-t-2xl border-b border-gray-200 px-6 pt-6 flex gap-6 overflow-x-auto">
                <button onclick="switchTab('profile')" id="tab-profile" class="pb-4 font-semibold text-amber-600 border-b-2 border-amber-600 whitespace-nowrap transition-colors">
                    <i class="fas fa-user-circle mr-2"></i>Profil & Pengaturan
                </button>
                <button onclick="switchTab('history')" id="tab-history" class="pb-4 font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap transition-colors">
                    <i class="fas fa-history mr-2"></i>Riwayat Reservasi
                </button>
            </div>

            <div class="bg-white shadow-sm border border-t-0 border-gray-100 rounded-b-2xl overflow-hidden min-h-[500px]">
                
                <!-- TAB 1: PROFILE & SETTINGS -->
                <div id="content-profile" class="block">
                    <form action="{{ route('customer.profile.update') }}" method="POST" enctype="multipart/form-data" class="divide-y divide-gray-100">
                        @csrf
                        @method('PUT')

                        <!-- Photo Section -->
                        <div class="p-6 sm:p-8 flex flex-col sm:flex-row items-center sm:items-start gap-6">
                            <div class="relative group">
                                <!-- Avatar Preview -->
                                <div class="w-32 h-32 rounded-full overflow-hidden bg-gradient-to-br from-amber-400 to-orange-500 flex items-center justify-center text-white text-5xl font-bold border-4 border-white shadow-md relative" id="photo-preview-container">
                                    @if($user->photo)
                                        <img src="{{ asset('storage/' . $user->photo) }}" alt="Foto Profil" class="w-full h-full object-cover" id="photo-preview">
                                    @else
                                        <span id="photo-initials">{{ strtoupper(substr($user->name, 0, 1)) }}</span>
                                        <img src="" alt="Foto Profil" class="w-full h-full object-cover hidden" id="photo-preview">
                                    @endif
                                    
                                    <!-- Overlay on hover -->
                                    <div class="absolute inset-0 bg-black/40 flex items-center justify-center opacity-0 group-hover:opacity-100 transition-opacity cursor-pointer" onclick="document.getElementById('photo').click()">
                                        <i class="fas fa-camera text-white text-2xl"></i>
                                    </div>
                                </div>
                            </div>

                            <div class="flex-1 text-center sm:text-left">
                                <h3 class="text-xl font-bold text-gray-800 mb-1">{{ $user->name }}</h3>
                                <p class="text-sm text-gray-500 mb-4">{{ $user->email }} • Pelanggan Terdaftar</p>
                                
                                <input type="file" id="photo" name="photo" class="hidden" accept="image/jpeg,image/png,image/jpg,image/gif" onchange="previewImage(this)">
                                
                                <button type="button" onclick="document.getElementById('photo').click()" class="inline-flex items-center px-4 py-2 bg-white border border-gray-300 rounded-lg font-medium text-sm text-gray-700 hover:bg-gray-50 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-all shadow-sm">
                                    <i class="fas fa-upload mr-2"></i> Pilih Foto Baru
                                </button>
                                <div id="file-name" class="mt-2 text-xs text-gray-500 font-medium truncate max-w-xs hidden"></div>
                            </div>
                        </div>

                        <!-- Details Section -->
                        <div class="p-6 sm:p-8 space-y-6">
                            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-100 pb-2 mb-4">Informasi Data Diri</h3>
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <!-- Name -->
                                <div>
                                    <label for="name" class="block text-sm font-medium text-gray-700 mb-1">Nama Lengkap <span class="text-red-500">*</span></label>
                                    <input type="text" id="name" name="name" value="{{ old('name', $user->name) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                </div>

                                <!-- Email -->
                                <div>
                                    <label for="email" class="block text-sm font-medium text-gray-700 mb-1">Alamat Email <span class="text-red-500">*</span></label>
                                    <input type="email" id="email" name="email" value="{{ old('email', $user->email) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                </div>

                                <!-- Phone -->
                                <div>
                                    <label for="phone" class="block text-sm font-medium text-gray-700 mb-1">Nomor Telepon/WhatsApp <span class="text-red-500">*</span></label>
                                    <input type="text" id="phone" name="phone" value="{{ old('phone', $user->phone) }}" required
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                </div>

                                <!-- Company -->
                                <div>
                                    <label for="company_name" class="block text-sm font-medium text-gray-700 mb-1">Instansi / Perusahaan</label>
                                    <input type="text" id="company_name" name="company_name" value="{{ old('company_name', $user->company_name) }}"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors" placeholder="Boleh dikosongkan jika pribadi">
                                </div>
                            </div>
                        </div>

                        <!-- Password Section -->
                        <div class="p-6 sm:p-8 bg-gray-50/50">
                            <h3 class="text-lg font-bold text-gray-800 border-b border-gray-200 pb-2 mb-4">Ubah Kata Sandi (Opsional)</h3>
                            <p class="text-sm text-gray-500 mb-4">Kosongkan kolom ini jika Anda tidak ingin mengubah kata sandi Anda.</p>
                            
                            <div class="grid grid-cols-1 sm:grid-cols-2 gap-6">
                                <div>
                                    <label for="password" class="block text-sm font-medium text-gray-700 mb-1">Kata Sandi Baru</label>
                                    <input type="password" id="password" name="password"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                </div>
                                <div>
                                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700 mb-1">Konfirmasi Kata Sandi Baru</label>
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-amber-500 focus:border-amber-500 transition-colors">
                                </div>
                            </div>
                        </div>

                        <!-- Footer / Submit -->
                        <div class="p-6 sm:p-8 flex justify-end gap-3 bg-gray-50">
                            <button type="submit" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all">
                                Simpan Perubahan
                            </button>
                        </div>
                    </form>
                </div>

                <!-- TAB 2: RESERVATION HISTORY -->
                <div id="content-history" class="hidden">
                    <div class="p-6 sm:p-8">
                        <div class="flex items-center justify-between mb-6">
                            <h3 class="text-xl font-bold text-gray-800">Riwayat Reservasi Saya</h3>
                            <a href="{{ route('rooms.index') }}" class="inline-flex items-center justify-center gap-2 text-sm font-semibold text-white bg-amber-500 hover:bg-amber-600 px-4 py-2 rounded-lg transition-all duration-300 shadow-sm">
                                <i class="fas fa-plus"></i> Pesan Ruangan
                            </a>
                        </div>

                        @if(isset($reservations) && $reservations->isEmpty())
                            <div class="bg-gray-50 rounded-2xl p-12 text-center border border-gray-100 mt-4">
                                <div class="w-20 h-20 bg-gray-200 rounded-full flex items-center justify-center mx-auto mb-4 text-gray-400 shadow-inner">
                                    <i class="fas fa-calendar-times text-3xl"></i>
                                </div>
                                <h4 class="text-lg font-bold text-gray-800 mb-2">Belum Ada Reservasi</h4>
                                <p class="text-gray-500 mb-6">Anda belum pernah melakukan pemesanan ruangan di Amaze Hotel.</p>
                                <a href="{{ route('rooms.index') }}" class="inline-block bg-amber-500 hover:bg-amber-600 text-white font-medium py-2.5 px-6 rounded-xl transition-colors shadow-md">Pesan Sekarang</a>
                            </div>
                        @else
                            <!-- Reservations Table (Desktop) -->
                            <div class="bg-white rounded-xl border border-gray-100 overflow-hidden hidden md:block">
                                <div class="overflow-x-auto">
                                    <table class="w-full text-left border-collapse">
                                        <thead>
                                            <tr class="bg-gray-50 border-b border-gray-100">
                                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">No. Reservasi</th>
                                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Ruang Meeting</th>
                                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Tanggal & Waktu</th>
                                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Paket & Peserta</th>
                                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider">Total Harga</th>
                                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Status</th>
                                                <th class="px-6 py-4 text-xs font-bold text-gray-500 uppercase tracking-wider text-center">Aksi</th>
                                            </tr>
                                        </thead>
                                        <tbody class="divide-y divide-gray-100">
                                            @foreach($reservations as $res)
                                                <tr class="hover:bg-gray-50/50 transition-colors">
                                                    <td class="px-6 py-4 font-bold text-gray-900 text-sm">
                                                        #{{ str_pad($res->id, 6, '0', STR_PAD_LEFT) }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-700 font-medium">
                                                        {{ $res->meetingRoom->name }}
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        <div>{{ \Carbon\Carbon::parse($res->date)->locale('id')->isoFormat('D MMMM Y') }}</div>
                                                        <div class="text-xs text-gray-400 mt-0.5"><i class="far fa-clock mr-1"></i>{{ $res->time }}</div>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm text-gray-500">
                                                        <div>{{ $res->foodPackage->name }}</div>
                                                        <div class="text-xs text-gray-400 mt-0.5"><i class="fas fa-users mr-1"></i>{{ $res->participants }} Orang</div>
                                                    </td>
                                                    <td class="px-6 py-4 text-sm font-bold text-gray-900">
                                                        Rp{{ number_format($res->total_price, 0, ',', '.') }}
                                                    </td>
                                                    <td class="px-6 py-4 text-center">
                                                        @if($res->status == 'sukses')
                                                            <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-emerald-200">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-emerald-500"></span> Sukses
                                                            </span>
                                                        @elseif($res->status == 'pending')
                                                            <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-amber-200">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-amber-500 animate-pulse"></span> Pending
                                                            </span>
                                                        @else
                                                            <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 text-xs font-semibold px-2.5 py-1 rounded-full border border-red-200">
                                                                <span class="w-1.5 h-1.5 rounded-full bg-red-500"></span> Dibatalkan
                                                            </span>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 text-center">
                                                        <div class="flex items-center justify-center gap-2">
                                                            @if($res->status === 'pending')
                                                                <a href="{{ route('payment.pay', $res->id) }}" class="inline-flex items-center gap-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition shadow-sm">
                                                                    <i class="fas fa-credit-card text-[10px]"></i> Bayar
                                                                </a>
                                                            @endif
                                                            <a href="{{ route('reservation.invoice', $res->id) }}" class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-1.5 rounded-lg transition">
                                                                <i class="fas fa-file-invoice text-[10px]"></i> Invoice
                                                            </a>
                                                        </div>
                                                    </td>
                                                </tr>
                                            @endforeach
                                        </tbody>
                                    </table>
                                </div>
                            </div>

                            <!-- Reservations Cards (Mobile) -->
                            <div class="md:hidden space-y-4">
                                @foreach($reservations as $res)
                                    <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm space-y-4">
                                        <div class="flex justify-between items-center pb-3 border-b border-gray-50">
                                            <span class="font-bold text-gray-800 text-sm">#{{ str_pad($res->id, 6, '0', STR_PAD_LEFT) }}</span>
                                            @if($res->status == 'sukses')
                                                <span class="inline-flex items-center gap-1 bg-emerald-50 text-emerald-700 text-xs font-semibold px-2 py-0.5 rounded-full border border-emerald-200">
                                                    Sukses
                                                </span>
                                            @elseif($res->status == 'pending')
                                                <span class="inline-flex items-center gap-1 bg-amber-50 text-amber-700 text-xs font-semibold px-2 py-0.5 rounded-full border border-amber-200">
                                                    Pending
                                                </span>
                                            @else
                                                <span class="inline-flex items-center gap-1 bg-red-50 text-red-700 text-xs font-semibold px-2 py-0.5 rounded-full border border-red-200">
                                                    Dibatalkan
                                                </span>
                                            @endif
                                        </div>

                                        <div class="grid grid-cols-2 gap-4 text-sm">
                                            <div>
                                                <span class="text-xs text-gray-400 block mb-0.5">Ruang Meeting</span>
                                                <span class="font-semibold text-gray-700">{{ $res->meetingRoom->name }}</span>
                                            </div>
                                            <div>
                                                <span class="text-xs text-gray-400 block mb-0.5">Tanggal & Waktu</span>
                                                <span class="font-semibold text-gray-700">{{ \Carbon\Carbon::parse($res->date)->locale('id')->isoFormat('D MMM Y') }} ({{ $res->time }})</span>
                                            </div>
                                            <div>
                                                <span class="text-xs text-gray-400 block mb-0.5">Paket</span>
                                                <span class="font-semibold text-gray-700">{{ $res->foodPackage->name }}</span>
                                            </div>
                                            <div>
                                                <span class="text-xs text-gray-400 block mb-0.5">Peserta</span>
                                                <span class="font-semibold text-gray-700">{{ $res->participants }} Orang</span>
                                            </div>
                                        </div>

                                        <div class="pt-3 border-t border-gray-50 flex items-center justify-between">
                                            <div>
                                                <span class="text-xs text-gray-400 block">Total Pembayaran</span>
                                                <span class="font-bold text-gray-900 text-base">Rp{{ number_format($res->total_price, 0, ',', '.') }}</span>
                                            </div>
                                            <div class="flex gap-2">
                                                @if($res->status === 'pending')
                                                    <a href="{{ route('payment.pay', $res->id) }}" class="inline-flex items-center gap-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-3 py-2 rounded-lg transition shadow-sm">
                                                        Bayar
                                                    </a>
                                                @endif
                                                <a href="{{ route('reservation.invoice', $res->id) }}" class="inline-flex items-center gap-1 bg-gray-100 hover:bg-gray-200 text-gray-700 text-xs font-semibold px-3 py-2 rounded-lg transition">
                                                    Invoice
                                                </a>
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @endif
                    </div>
                </div>

            </div>
        </div>
    </div>

    <script>
        function switchTab(tabName) {
            // Toggle contents
            const profileContent = document.getElementById('content-profile');
            const historyContent = document.getElementById('content-history');
            
            // Toggle tab buttons styling
            const tabProfile = document.getElementById('tab-profile');
            const tabHistory = document.getElementById('tab-history');
            
            if (tabName === 'profile') {
                profileContent.classList.remove('hidden');
                profileContent.classList.add('block');
                historyContent.classList.remove('block');
                historyContent.classList.add('hidden');
                
                tabProfile.className = "pb-4 font-semibold text-amber-600 border-b-2 border-amber-600 whitespace-nowrap transition-colors";
                tabHistory.className = "pb-4 font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap transition-colors";
            } else {
                profileContent.classList.remove('block');
                profileContent.classList.add('hidden');
                historyContent.classList.remove('hidden');
                historyContent.classList.add('block');
                
                tabProfile.className = "pb-4 font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap transition-colors";
                tabHistory.className = "pb-4 font-semibold text-amber-600 border-b-2 border-amber-600 whitespace-nowrap transition-colors";
            }
        }

        function previewImage(input) {
            const file = input.files[0];
            if (file) {
                // Check file size (10MB limit)
                const maxSize = 10 * 1024 * 1024;
                if (file.size > maxSize) {
                    alert('Ukuran file foto maksimal adalah 10MB.');
                    input.value = '';
                    return;
                }
                
                const reader = new FileReader();
                reader.onload = function(e) {
                    const imgPreview = document.getElementById('photo-preview');
                    const initials = document.getElementById('photo-initials');
                    
                    if (imgPreview) {
                        imgPreview.src = e.target.result;
                        imgPreview.classList.remove('hidden');
                    }
                    if (initials) {
                        initials.classList.add('hidden');
                    }
                }
                reader.readAsDataURL(file);
                
                // Show file name
                const fileNameDiv = document.getElementById('file-name');
                if (fileNameDiv) {
                    fileNameDiv.textContent = file.name;
                    fileNameDiv.classList.remove('hidden');
                }
            }
        }
    </script>
</x-app-layout>
