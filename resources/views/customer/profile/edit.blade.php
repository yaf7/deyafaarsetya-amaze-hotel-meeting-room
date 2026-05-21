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

            @php
                $activeReschedules = $reservations->filter(function($res) {
                    return in_array($res->reschedule_status, ['approved', 'rejected']) && !$res->reschedule_notification_read;
                });
            @endphp

            @if($activeReschedules->count() > 0)
                <div class="space-y-4 mb-6">
                    @foreach($activeReschedules as $res)
                        @if($res->reschedule_status === 'approved')
                            <div class="bg-gradient-to-r from-emerald-50 to-teal-50 border border-emerald-100 rounded-2xl p-5 shadow-sm flex items-start gap-4">
                                <div class="w-10 h-10 bg-emerald-500 rounded-full flex items-center justify-center text-white shrink-0 shadow-soft">
                                    <i class="fas fa-calendar-check"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <h4 class="font-bold text-emerald-800 text-base">Reschedule Disetujui</h4>
                                        <span class="text-xs font-semibold text-emerald-600 bg-emerald-100/60 px-3 py-1 rounded-full w-max">Reservasi #{{ str_pad($res->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <p class="text-sm text-emerald-700/90 mt-2 leading-relaxed">
                                        Pengajuan reschedule Anda untuk <strong>{{ $res->meetingRoom->name }}</strong> telah <strong>disetujui</strong> oleh admin. Acara Anda telah dijadwalkan ulang pada hari <strong>{{ \Carbon\Carbon::parse($res->date)->locale('id')->isoFormat('dddd, D MMMM Y') }}</strong> pukul <strong>{{ $res->time }}</strong>.
                                    </p>
                                </div>
                            </div>
                        @elseif($res->reschedule_status === 'rejected')
                            <div class="bg-gradient-to-r from-rose-50 to-orange-50 border border-rose-100 rounded-2xl p-5 shadow-sm flex items-start gap-4">
                                <div class="w-10 h-10 bg-rose-500 rounded-full flex items-center justify-center text-white shrink-0 shadow-soft">
                                    <i class="fas fa-calendar-times"></i>
                                </div>
                                <div class="flex-grow">
                                    <div class="flex flex-col sm:flex-row sm:items-center justify-between gap-2">
                                        <h4 class="font-bold text-rose-800 text-base">Reschedule Ditolak</h4>
                                        <span class="text-xs font-semibold text-rose-600 bg-rose-100/60 px-3 py-1 rounded-full w-max">Reservasi #{{ str_pad($res->id, 6, '0', STR_PAD_LEFT) }}</span>
                                    </div>
                                    <p class="text-sm text-rose-700/90 mt-2 leading-relaxed">
                                        Pengajuan reschedule Anda untuk <strong>{{ $res->meetingRoom->name }}</strong> telah <strong>ditolak</strong> oleh admin.
                                    </p>
                                    @if($res->reschedule_rejection_reason)
                                        <div class="mt-3 bg-white/60 backdrop-blur-sm border border-rose-100/80 rounded-xl p-3 text-xs sm:text-sm text-rose-800 italic font-medium">
                                            <span class="font-bold not-italic block text-rose-700 text-xs mb-1"><i class="fas fa-info-circle mr-1"></i> Alasan Penolakan:</span>
                                            "{{ $res->reschedule_rejection_reason }}"
                                        </div>
                                    @endif
                                </div>
                            </div>
                        @endif
                    @endforeach
                </div>
            @endif

            <!-- Tabs Navigation -->
            <div class="bg-white rounded-t-2xl border-b border-gray-200 px-6 pt-6 flex gap-6 overflow-x-auto">
                <button onclick="switchTab('profile')" id="tab-profile" class="pb-4 font-semibold text-amber-600 border-b-2 border-amber-600 whitespace-nowrap transition-colors">
                    <i class="fas fa-user-circle mr-2"></i>Profil & Pengaturan
                </button>
                <button onclick="switchTab('history')" id="tab-history" class="pb-4 font-medium text-gray-500 border-b-2 border-transparent hover:text-gray-700 whitespace-nowrap transition-colors flex items-center gap-2">
                    <i class="fas fa-history"></i>Riwayat Reservasi
                    @if($activeReschedules->count() > 0)
                        <span class="bg-rose-500 text-white text-[10px] font-bold px-2 py-0.5 rounded-full animate-pulse">{{ $activeReschedules->count() }}</span>
                    @endif
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
                                                @php
                                                    $eventDate = \Carbon\Carbon::parse($res->date);
                                                    $canReschedule = ($res->status === 'sukses' && $res->reschedule_count == 0 && ($res->reschedule_status === null || $res->reschedule_status === 'rejected') && now()->diffInDays($eventDate, false) >= 3);
                                                @endphp
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

                                                        @if($res->reschedule_status === 'pending')
                                                            <div class="mt-1">
                                                                <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-indigo-200">
                                                                    <i class="fas fa-clock text-[9px] animate-pulse"></i> Pending Reschedule
                                                                </span>
                                                            </div>
                                                        @elseif($res->reschedule_status === 'approved')
                                                            <div class="mt-1">
                                                                <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-sky-200">
                                                                    <i class="fas fa-calendar-check text-[9px]"></i> Rescheduled
                                                                </span>
                                                            </div>
                                                        @elseif($res->reschedule_status === 'rejected')
                                                            <div class="mt-1">
                                                                <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-rose-200 cursor-help" title="Alasan: {{ $res->reschedule_rejection_reason }}">
                                                                    <i class="fas fa-times-circle text-[9px]"></i> Reschedule Ditolak
                                                                </span>
                                                                <div class="text-[9px] text-rose-500 mt-0.5 italic max-w-[150px] truncate mx-auto" title="{{ $res->reschedule_rejection_reason }}">"{{ $res->reschedule_rejection_reason }}"</div>
                                                            </div>
                                                        @endif
                                                    </td>
                                                    <td class="px-6 py-4 text-center">
                                                        <div class="flex items-center justify-center gap-2">
                                                            @if($res->status === 'pending')
                                                                <a href="{{ route('payment.pay', $res->id) }}" class="inline-flex items-center gap-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition shadow-sm">
                                                                    <i class="fas fa-credit-card text-[10px]"></i> Bayar
                                                                </a>
                                                            @endif
                                                            @if($canReschedule)
                                                                <button type="button" 
                                                                        onclick="openRescheduleModal({{ $res->id }}, '{{ $res->meetingRoom->name }}', '{{ $res->date->format('Y-m-d') }}', '{{ $res->time }}', '{{ $res->foodPackage->name }}')"
                                                                        data-room-id="{{ $res->meeting_room_id }}"
                                                                        class="inline-flex items-center gap-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-3 py-1.5 rounded-lg transition shadow-sm">
                                                                    <i class="fas fa-calendar-alt text-[10px]"></i> Reschedule
                                                                </button>
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
                                     @php
                                         $eventDate = \Carbon\Carbon::parse($res->date);
                                         $canReschedule = ($res->status === 'sukses' && $res->reschedule_count == 0 && ($res->reschedule_status === null || $res->reschedule_status === 'rejected') && now()->diffInDays($eventDate, false) >= 3);
                                     @endphp
                                     <div class="bg-white border border-gray-100 rounded-xl p-5 shadow-sm space-y-4">
                                         <div class="flex justify-between items-center pb-3 border-b border-gray-50">
                                             <div class="flex flex-col gap-1">
                                                 <span class="font-bold text-gray-800 text-sm">#{{ str_pad($res->id, 6, '0', STR_PAD_LEFT) }}</span>
                                             </div>
                                             <div class="flex flex-col items-end gap-1">
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

                                                 @if($res->reschedule_status === 'pending')
                                                     <span class="inline-flex items-center gap-1 bg-indigo-50 text-indigo-700 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-indigo-200">
                                                         Pending Reschedule
                                                     </span>
                                                 @elseif($res->reschedule_status === 'approved')
                                                     <span class="inline-flex items-center gap-1 bg-sky-50 text-sky-700 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-sky-200">
                                                         Rescheduled
                                                     </span>
                                                 @elseif($res->reschedule_status === 'rejected')
                                                     <span class="inline-flex items-center gap-1 bg-rose-50 text-rose-700 text-[10px] font-semibold px-2 py-0.5 rounded-full border border-rose-200" title="Alasan: {{ $res->reschedule_rejection_reason }}">
                                                         Reschedule Ditolak
                                                     </span>
                                                 @endif
                                             </div>
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
                                                 @if($canReschedule)
                                                     <button type="button" 
                                                             onclick="openRescheduleModal({{ $res->id }}, '{{ $res->meetingRoom->name }}', '{{ $res->date->format('Y-m-d') }}', '{{ $res->time }}', '{{ $res->foodPackage->name }}')"
                                                             data-room-id="{{ $res->meeting_room_id }}"
                                                             class="inline-flex items-center gap-1 bg-amber-500 hover:bg-amber-600 text-white text-xs font-semibold px-3 py-2 rounded-lg transition shadow-sm">
                                                         Reschedule
                                                     </button>
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

    <!-- Reschedule Modal -->
    <div id="reschedule-modal" class="fixed inset-0 z-50 overflow-y-auto bg-black/60 backdrop-blur-sm hidden items-center justify-center p-4">
        <div class="bg-white rounded-3xl max-w-xl w-full shadow-2xl overflow-hidden border border-gray-100 transform scale-95 transition-transform duration-300" id="reschedule-modal-card">
            <!-- Modal Header -->
            <div class="bg-gradient-to-r from-amber-500 to-orange-600 px-6 py-5 text-white relative">
                <h3 class="text-xl font-bold">Reschedule Reservasi</h3>
                <p class="text-sm text-white/80 mt-1" id="reschedule-modal-room-name">Ruang Meeting: -</p>
                <button type="button" onclick="closeRescheduleModal()" class="absolute top-5 right-5 text-white/80 hover:text-white transition-colors">
                    <i class="fas fa-times text-lg"></i>
                </button>
            </div>

            <!-- Modal Body -->
            <form id="reschedule-form" method="POST" action="" class="p-6 space-y-6">
                @csrf
                
                <!-- Current Schedule Info -->
                <div class="bg-amber-50/50 border border-amber-100 rounded-2xl p-4 flex gap-4 items-center">
                    <div class="w-12 h-12 rounded-xl bg-amber-100 flex items-center justify-center text-amber-600 text-lg">
                        <i class="fas fa-calendar-day"></i>
                    </div>
                    <div>
                        <p class="text-xs text-gray-500 font-medium">Jadwal Saat Ini</p>
                        <p class="font-bold text-gray-800 text-sm" id="current-schedule-info">-</p>
                    </div>
                </div>

                <!-- Input New Date -->
                <div>
                    <label for="reschedule-date" class="block text-sm font-bold text-gray-800 mb-2">
                        <i class="far fa-calendar-alt text-amber-500 mr-1.5"></i> Pilih Tanggal Baru
                    </label>
                    <input type="date" id="reschedule-date" name="date" required
                           min="{{ now()->addDay()->format('Y-m-d') }}"
                           onchange="checkAvailabilityForReschedule()"
                           class="w-full px-4 py-3 border border-gray-200 rounded-xl focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all">
                    
                    <!-- Availability Alert -->
                    <div id="reschedule-date-alert" class="hidden mt-3 rounded-xl p-3.5 text-sm font-medium flex items-center gap-3"></div>
                </div>

                <!-- Input New Session -->
                <div>
                    <label class="block text-sm font-bold text-gray-800 mb-3">
                        <i class="far fa-clock text-amber-500 mr-1.5"></i> Pilih Sesi Waktu Baru
                    </label>
                    
                    <!-- Hidden Input for Session -->
                    <input type="hidden" name="session_slot" id="selected-session-slot" required>

                    <!-- Session Cards Grid -->
                    <div class="grid grid-cols-1 sm:grid-cols-2 gap-3" id="reschedule-sessions-container">
                        @php
                            $slots = [
                                'Sesi Pagi (08:00 - 12:00)',
                                'Sesi Siang (14:00 - 18:00)',
                                'Sesi Malam (18:00 - 22:00)',
                                'Sesi Fullboard (Seharian Penuh)'
                            ];
                            $shortLabels = [
                                'Sesi Pagi (08:00 - 12:00)' => ['title' => 'Sesi Pagi', 'time' => '08:00 - 12:00', 'icon' => 'fa-sun text-amber-500'],
                                'Sesi Siang (14:00 - 18:00)' => ['title' => 'Sesi Siang', 'time' => '14:00 - 18:00', 'icon' => 'fa-cloud-sun text-orange-500'],
                                'Sesi Malam (18:00 - 22:00)' => ['title' => 'Sesi Malam', 'time' => '18:00 - 22:00', 'icon' => 'fa-moon text-indigo-500'],
                                'Sesi Fullboard (Seharian Penuh)' => ['title' => 'Fullboard', 'time' => 'Seharian Penuh', 'icon' => 'fa-business-time text-emerald-500']
                            ];
                        @endphp

                        @foreach($slots as $slot)
                            @php $lbl = $shortLabels[$slot]; @endphp
                            <div class="session-card border border-gray-200 rounded-2xl p-4 flex items-center justify-between cursor-pointer transition-all duration-300 hover:border-amber-300 bg-white relative overflow-hidden" 
                                 data-session-value="{{ $slot }}"
                                 onclick="selectRescheduleSession(this)">
                                
                                <div class="flex items-center gap-3">
                                    <div class="w-9 h-9 rounded-lg bg-gray-50 flex items-center justify-center text-base">
                                        <i class="fas {{ $lbl['icon'] }}"></i>
                                    </div>
                                    <div>
                                        <h4 class="font-bold text-gray-800 text-sm">{{ $lbl['title'] }}</h4>
                                        <p class="text-xs text-gray-400 mt-0.5">{{ $lbl['time'] }}</p>
                                    </div>
                                </div>

                                <!-- Checkmark indicator -->
                                <div class="checkmark-indicator hidden w-5 h-5 bg-amber-500 rounded-full items-center justify-center text-white text-[10px] shadow-sm z-10">
                                    <i class="fas fa-check"></i>
                                </div>
                                
                                <!-- Diagonal disabled stripes using CSS gradient inline -->
                                <div class="stripe-overlay absolute inset-0 opacity-0 transition-opacity duration-300"
                                     style="background: repeating-linear-gradient(45deg, #f3f4f6, #f3f4f6 10px, #ffffff 10px, #ffffff 20px); pointer-events: none;"></div>
                            </div>
                        @endforeach
                    </div>
                </div>

                <!-- Footer / Action Buttons -->
                <div class="flex justify-end gap-3 pt-4 border-t border-gray-100">
                    <button type="button" onclick="closeRescheduleModal()" class="px-5 py-2.5 border border-gray-200 text-gray-500 hover:text-gray-700 bg-white hover:bg-gray-50 font-medium rounded-xl transition shadow-sm text-sm">
                        Batal
                    </button>
                    <button type="submit" id="reschedule-submit-btn" class="px-6 py-2.5 bg-amber-500 hover:bg-amber-600 text-white font-medium rounded-xl shadow-md hover:shadow-lg transition-all text-sm">
                        Ajukan Reschedule
                    </button>
                </div>
            </form>
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

        let activeRoomId = null;
        let activeReservationId = null;
        let isFullboardLocked = false;

        function isFullDayOrMorePackage(pkgName) {
            if (!pkgName) return false;
            const lower = pkgName.toLowerCase();
            return lower.includes('full day') || lower.includes('full board') || lower.includes('residential');
        }

        function openRescheduleModal(resId, roomName, currentDate, currentTime, packageName) {
            // Set dynamic fields
            activeRoomId = null;
            activeReservationId = resId;
            isFullboardLocked = isFullDayOrMorePackage(packageName);
            
            // Get Room ID from the event target or parameter
            const btn = event.currentTarget;
            activeRoomId = btn.getAttribute('data-room-id');
            
            document.getElementById('reschedule-modal-room-name').textContent = "Ruang Meeting: " + roomName;
            
            // Format current schedule
            const formattedDate = new Date(currentDate).toLocaleDateString('id-ID', {
                weekday: 'long', year: 'numeric', month: 'long', day: 'numeric'
            });
            document.getElementById('current-schedule-info').textContent = formattedDate + " (" + currentTime + ")";
            
            // Set form action
            document.getElementById('reschedule-form').action = "/reservations/" + resId + "/reschedule";
            
            // Reset fields
            document.getElementById('reschedule-date').value = '';
            document.getElementById('selected-session-slot').value = '';
            
            // Reset availability alert
            const dateAlert = document.getElementById('reschedule-date-alert');
            dateAlert.classList.add('hidden');
            dateAlert.innerHTML = '';
            
            // Reset submit button
            const submitBtn = document.getElementById('reschedule-submit-btn');
            submitBtn.disabled = false;
            submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
            
            // Reset session cards state
            const cards = document.querySelectorAll('#reschedule-sessions-container .session-card');
            cards.forEach(card => {
                card.classList.remove('border-amber-600', 'bg-amber-50/40', 'opacity-60', 'pointer-events-none');
                card.querySelector('.checkmark-indicator').classList.add('hidden');
                card.querySelector('.checkmark-indicator').classList.remove('flex');
                card.querySelector('.stripe-overlay').classList.remove('opacity-100');
                card.querySelector('.stripe-overlay').classList.add('opacity-0');
            });

            // Jika paket Full Day/Full Board/Residential, kunci ke Fullboard
            if (isFullboardLocked) {
                cards.forEach(card => {
                    const val = card.getAttribute('data-session-value');
                    if (val.includes('Fullboard')) {
                        // Auto-select Fullboard
                        card.classList.add('border-amber-600', 'bg-amber-50/40');
                        card.querySelector('.checkmark-indicator').classList.remove('hidden');
                        card.querySelector('.checkmark-indicator').classList.add('flex');
                        document.getElementById('selected-session-slot').value = val;
                    } else {
                        // Disable semua sesi lain
                        card.classList.add('opacity-60', 'pointer-events-none');
                        card.querySelector('.stripe-overlay').classList.remove('opacity-0');
                        card.querySelector('.stripe-overlay').classList.add('opacity-100');
                    }
                });
            }

            // Show modal
            const modal = document.getElementById('reschedule-modal');
            modal.classList.remove('hidden');
            modal.classList.add('flex');
            setTimeout(() => {
                document.getElementById('reschedule-modal-card').classList.remove('scale-95');
                document.getElementById('reschedule-modal-card').classList.add('scale-100');
            }, 10);
        }

        function closeRescheduleModal() {
            document.getElementById('reschedule-modal-card').classList.remove('scale-100');
            document.getElementById('reschedule-modal-card').classList.add('scale-95');
            setTimeout(() => {
                const modal = document.getElementById('reschedule-modal');
                modal.classList.remove('flex');
                modal.classList.add('hidden');
            }, 150);
        }

        function selectRescheduleSession(element) {
            if (element.classList.contains('pointer-events-none')) return;
            
            // Jika fullboard locked, jangan izinkan ubah sesi
            if (isFullboardLocked) return;
            
            // Deselect others
            const cards = document.querySelectorAll('#reschedule-sessions-container .session-card');
            cards.forEach(card => {
                card.classList.remove('border-amber-600', 'bg-amber-50/40');
                card.querySelector('.checkmark-indicator').classList.add('hidden');
                card.querySelector('.checkmark-indicator').classList.remove('flex');
            });

            // Select clicked
            element.classList.add('border-amber-600', 'bg-amber-50/40');
            element.querySelector('.checkmark-indicator').classList.remove('hidden');
            element.querySelector('.checkmark-indicator').classList.add('flex');
            
            // Set input value
            document.getElementById('selected-session-slot').value = element.getAttribute('data-session-value');
        }

        function checkAvailabilityForReschedule() {
            const dateVal = document.getElementById('reschedule-date').value;
            const dateAlert = document.getElementById('reschedule-date-alert');
            const submitBtn = document.getElementById('reschedule-submit-btn');
            
            if (!dateVal || !activeRoomId) {
                dateAlert.classList.add('hidden');
                return;
            }

            // Fetch booked sessions from API
            fetch(`/api/check-availability?room_id=${activeRoomId}&date=${dateVal}`)
                .then(response => response.json())
                .then(data => {
                    const bookedSessions = data.booked_sessions || [];
                    let allDisabled = false;
                    
                    const cards = document.querySelectorAll('#reschedule-sessions-container .session-card');
                    let disabledCount = 0;
                    
                    cards.forEach(card => {
                        const val = card.getAttribute('data-session-value');
                        
                        // Reset card state first
                        card.classList.remove('opacity-60', 'pointer-events-none', 'border-amber-600', 'bg-amber-50/40');
                        card.querySelector('.checkmark-indicator').classList.add('hidden');
                        card.querySelector('.checkmark-indicator').classList.remove('flex');
                        card.querySelector('.stripe-overlay').classList.remove('opacity-100');
                        card.querySelector('.stripe-overlay').classList.add('opacity-0');

                        // Jika fullboard locked, kunci sesi selain Fullboard
                        if (isFullboardLocked) {
                            if (val.includes('Fullboard')) {
                                // Cek apakah Fullboard itu sendiri booked
                                const isBooked = bookedSessions.includes(val);
                                const hasFullboard = bookedSessions.some(s => s.includes('Fullboard'));
                                const hasAnyBooking = bookedSessions.length > 0;
                                
                                if (isBooked || hasFullboard || hasAnyBooking) {
                                    card.classList.add('opacity-60', 'pointer-events-none');
                                    card.querySelector('.stripe-overlay').classList.remove('opacity-0');
                                    card.querySelector('.stripe-overlay').classList.add('opacity-100');
                                    document.getElementById('selected-session-slot').value = '';
                                    allDisabled = true;
                                } else {
                                    // Auto-select Fullboard
                                    card.classList.add('border-amber-600', 'bg-amber-50/40');
                                    card.querySelector('.checkmark-indicator').classList.remove('hidden');
                                    card.querySelector('.checkmark-indicator').classList.add('flex');
                                    document.getElementById('selected-session-slot').value = val;
                                }
                            } else {
                                card.classList.add('opacity-60', 'pointer-events-none');
                                card.querySelector('.stripe-overlay').classList.remove('opacity-0');
                                card.querySelector('.stripe-overlay').classList.add('opacity-100');
                            }
                            return;
                        }

                        // Check if session is fullboard or this session is booked
                        const isBooked = bookedSessions.includes(val);
                        const hasFullboard = bookedSessions.some(s => s.includes('Fullboard'));
                        const isNewFullboard = val.includes('Fullboard');
                        
                        let shouldDisable = isBooked || hasFullboard || (isNewFullboard && bookedSessions.length > 0);
                        
                        // Handle Siang & Malam conflict rule
                        const hasSiang = bookedSessions.some(s => s.includes('Siang'));
                        const hasMalam = bookedSessions.some(s => s.includes('Malam'));
                        if (!shouldDisable) {
                            if (hasSiang && val.includes('Malam')) shouldDisable = true;
                            if (hasMalam && val.includes('Siang')) shouldDisable = true;
                        }

                        if (shouldDisable) {
                            card.classList.add('opacity-60', 'pointer-events-none');
                            card.querySelector('.stripe-overlay').classList.remove('opacity-0');
                            card.querySelector('.stripe-overlay').classList.add('opacity-100');
                            disabledCount++;
                        }
                    });

                    // Clear selected session if it got disabled (non-locked mode)
                    if (!isFullboardLocked) {
                        const selectedVal = document.getElementById('selected-session-slot').value;
                        if (selectedVal) {
                            const selectedCard = Array.from(cards).find(c => c.getAttribute('data-session-value') === selectedVal);
                            if (selectedCard && selectedCard.classList.contains('pointer-events-none')) {
                                document.getElementById('selected-session-slot').value = '';
                            }
                        }
                        allDisabled = (disabledCount >= cards.length);
                    }

                    // Show/hide availability alert and toggle submit button
                    const formattedDate = new Date(dateVal).toLocaleDateString('id-ID', {
                        weekday: 'long', day: 'numeric', month: 'long', year: 'numeric'
                    });

                    if (allDisabled) {
                        dateAlert.className = 'mt-3 rounded-xl p-3.5 text-sm font-medium flex items-center gap-3 bg-red-50 border border-red-100 text-red-700';
                        dateAlert.innerHTML = '<div class="w-8 h-8 bg-red-100 rounded-lg flex items-center justify-center shrink-0"><i class="fas fa-exclamation-circle text-red-500"></i></div>' +
                            '<span>Tanggal <strong>' + formattedDate + '</strong> sudah penuh. Silakan pilih tanggal lain.</span>';
                        dateAlert.classList.remove('hidden');
                        submitBtn.disabled = true;
                        submitBtn.classList.add('opacity-50', 'cursor-not-allowed');
                    } else if (bookedSessions.length > 0 && !isFullboardLocked) {
                        dateAlert.className = 'mt-3 rounded-xl p-3.5 text-sm font-medium flex items-center gap-3 bg-amber-50 border border-amber-100 text-amber-700';
                        dateAlert.innerHTML = '<div class="w-8 h-8 bg-amber-100 rounded-lg flex items-center justify-center shrink-0"><i class="fas fa-info-circle text-amber-500"></i></div>' +
                            '<span>Beberapa sesi pada tanggal ini sudah terisi. Silakan pilih sesi yang tersedia.</span>';
                        dateAlert.classList.remove('hidden');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    } else {
                        dateAlert.className = 'mt-3 rounded-xl p-3.5 text-sm font-medium flex items-center gap-3 bg-emerald-50 border border-emerald-100 text-emerald-700';
                        dateAlert.innerHTML = '<div class="w-8 h-8 bg-emerald-100 rounded-lg flex items-center justify-center shrink-0"><i class="fas fa-check-circle text-emerald-500"></i></div>' +
                            '<span>Tanggal <strong>' + formattedDate + '</strong> tersedia untuk reschedule.</span>';
                        dateAlert.classList.remove('hidden');
                        submitBtn.disabled = false;
                        submitBtn.classList.remove('opacity-50', 'cursor-not-allowed');
                    }
                })
                .catch(err => console.error("Error checking availability:", err));
        }
    </script>
</x-app-layout>
