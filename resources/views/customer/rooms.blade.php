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

    <!-- Availability Board Section -->
    <section class="py-8 sm:py-12 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="avail-board mb-10">
                <!-- Board Header -->
                <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
                    <div>
                        <h2 class="text-xl sm:text-2xl font-bold text-gray-800 flex items-center gap-2">
                            <i class="fas fa-calendar-check text-primary-500"></i>
                            Cek Ketersediaan
                        </h2>
                        <p class="text-gray-500 text-sm mt-1">Pilih tanggal untuk melihat sesi yang tersedia</p>
                    </div>
                    <div class="flex items-center gap-3">
                        <label for="avail-date" class="text-sm font-semibold text-gray-700 whitespace-nowrap">
                            <i class="fas fa-calendar text-amber-500 mr-1"></i>Tanggal:
                        </label>
                        <input type="date" id="avail-date" 
                               class="input-modern !py-2.5 !text-sm max-w-[200px]"
                               min="{{ date('Y-m-d') }}" 
                               value="{{ date('Y-m-d') }}">
                    </div>
                </div>

                <!-- Legend -->
                <div class="flex flex-wrap items-center gap-4 mb-5 text-xs">
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-green-500"></span>
                        Tersedia
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-amber-500"></span>
                        Terbatas
                    </span>
                    <span class="flex items-center gap-1.5">
                        <span class="w-3 h-3 rounded-full bg-red-500"></span>
                        Penuh
                    </span>
                </div>

                <!-- Desktop: Table Grid -->
                <div class="hidden sm:block overflow-x-auto">
                    <table class="w-full" id="avail-table-desktop">
                        <thead>
                            <tr class="border-b-2 border-gray-100">
                                <th class="text-left py-3 px-4 text-sm font-bold text-gray-700 w-1/5">Ruangan</th>
                                <th class="text-center py-3 px-3 text-sm font-bold text-gray-700">
                                    <div class="flex flex-col items-center">
                                        <span>🌅 Pagi</span>
                                        <span class="text-[10px] text-gray-400 font-normal">08:00 - 12:00</span>
                                    </div>
                                </th>
                                <th class="text-center py-3 px-3 text-sm font-bold text-gray-700">
                                    <div class="flex flex-col items-center">
                                        <span>☀️ Siang</span>
                                        <span class="text-[10px] text-gray-400 font-normal">14:00 - 18:00</span>
                                    </div>
                                </th>
                                <th class="text-center py-3 px-3 text-sm font-bold text-gray-700">
                                    <div class="flex flex-col items-center">
                                        <span>🌙 Malam</span>
                                        <span class="text-[10px] text-gray-400 font-normal">18:00 - 22:00</span>
                                    </div>
                                </th>
                                <th class="text-center py-3 px-3 text-sm font-bold text-gray-700">
                                    <div class="flex flex-col items-center">
                                        <span>📅 Fullboard</span>
                                        <span class="text-[10px] text-gray-400 font-normal">Seharian Penuh</span>
                                    </div>
                                </th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rooms as $room)
                                <tr class="border-b border-gray-50" data-room-id="{{ $room->id }}">
                                    <td class="py-4 px-4">
                                        <div class="flex items-center gap-3">
                                            <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0">
                                                <img src="{{ $room->photo ? asset('storage/' . $room->photo) : 'https://images.unsplash.com/photo-1517502884422-41eaead166d4?w=80&h=80&fit=crop' }}"
                                                     alt="{{ $room->name }}"
                                                     class="w-full h-full object-cover">
                                            </div>
                                            <span class="font-semibold text-gray-800 text-sm">{{ $room->name }}</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2" data-session="pagi">
                                        <div class="avail-cell avail-cell--loading">
                                            <span class="text-xs text-gray-400">...</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2" data-session="siang">
                                        <div class="avail-cell avail-cell--loading">
                                            <span class="text-xs text-gray-400">...</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2" data-session="malam">
                                        <div class="avail-cell avail-cell--loading">
                                            <span class="text-xs text-gray-400">...</span>
                                        </div>
                                    </td>
                                    <td class="py-3 px-2" data-session="fullboard">
                                        <div class="avail-cell avail-cell--loading">
                                            <span class="text-xs text-gray-400">...</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                <!-- Mobile: Card Layout -->
                <div class="block sm:hidden space-y-4" id="avail-cards-mobile">
                    @foreach ($rooms as $room)
                        <div class="border border-gray-200 rounded-xl p-4" data-room-id="{{ $room->id }}">
                            <div class="flex items-center gap-3 mb-3">
                                <div class="w-10 h-10 rounded-lg overflow-hidden flex-shrink-0">
                                    <img src="{{ $room->photo ? asset('storage/' . $room->photo) : 'https://images.unsplash.com/photo-1517502884422-41eaead166d4?w=80&h=80&fit=crop' }}"
                                         alt="{{ $room->name }}"
                                         class="w-full h-full object-cover">
                                </div>
                                <span class="font-semibold text-gray-800">{{ $room->name }}</span>
                            </div>
                            <div class="grid grid-cols-2 gap-2">
                                <div data-session="pagi">
                                    <div class="avail-cell avail-cell--loading">
                                        <span class="text-xs text-gray-400">🌅 Pagi</span>
                                        <span class="text-[10px] text-gray-400">Loading...</span>
                                    </div>
                                </div>
                                <div data-session="siang">
                                    <div class="avail-cell avail-cell--loading">
                                        <span class="text-xs text-gray-400">☀️ Siang</span>
                                        <span class="text-[10px] text-gray-400">Loading...</span>
                                    </div>
                                </div>
                                <div data-session="malam">
                                    <div class="avail-cell avail-cell--loading">
                                        <span class="text-xs text-gray-400">🌙 Malam</span>
                                        <span class="text-[10px] text-gray-400">Loading...</span>
                                    </div>
                                </div>
                                <div data-session="fullboard">
                                    <div class="avail-cell avail-cell--loading">
                                        <span class="text-xs text-gray-400">📅 Full</span>
                                        <span class="text-[10px] text-gray-400">Loading...</span>
                                    </div>
                                </div>
                            </div>
                        </div>
                    @endforeach
                </div>
            </div>
        </div>
    </section>

    <!-- Rooms Grid -->
    <section class="pb-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <!-- Filter Info -->
            <div class="flex items-center justify-between mb-10">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800 mb-1">Pilih Ruangan</h2>
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

    @push('scripts')
    <script>
        (function() {
            const rooms = @json($rooms->map(fn($r) => ['id' => $r->id, 'name' => $r->name]));
            const dateInput = document.getElementById('avail-date');

            const SESSION_MAP = {
                pagi: 'Sesi Pagi (08:00 - 12:00)',
                siang: 'Sesi Siang (14:00 - 18:00)',
                malam: 'Sesi Malam (18:00 - 22:00)',
                fullboard: 'Sesi Fullboard (Seharian Penuh)'
            };

            const SESSION_ICONS = {
                pagi: '🌅',
                siang: '☀️',
                malam: '🌙',
                fullboard: '📅'
            };

            const SESSION_LABELS = {
                pagi: 'Pagi',
                siang: 'Siang',
                malam: 'Malam',
                fullboard: 'Full'
            };

            function renderCell(roomId, sessionKey, status, isMobile) {
                const sessionVal = SESSION_MAP[sessionKey];
                const icon = SESSION_ICONS[sessionKey];
                const label = SESSION_LABELS[sessionKey];

                if (status === 'available') {
                    const bookUrl = `/rooms/${roomId}/reserve?date=${dateInput.value}&session=${encodeURIComponent(sessionVal)}`;
                    if (isMobile) {
                        return `<div class="avail-cell avail-cell--available">
                            <span class="text-xs font-semibold text-green-700">${icon} ${label}</span>
                            <a href="${bookUrl}" class="mt-1 text-[10px] font-bold text-green-600 hover:text-green-800 underline">Pesan →</a>
                        </div>`;
                    }
                    return `<div class="avail-cell avail-cell--available">
                        <span class="text-xs font-bold text-green-700">✅ Tersedia</span>
                        <a href="${bookUrl}" class="mt-1 text-[10px] font-bold text-white bg-green-500 hover:bg-green-600 px-3 py-1 rounded-full transition-colors">Pesan</a>
                    </div>`;
                } else if (status === 'limited') {
                    const bookUrl = `/rooms/${roomId}/reserve?date=${dateInput.value}&session=${encodeURIComponent(sessionVal)}`;
                    if (isMobile) {
                        return `<div class="avail-cell avail-cell--limited">
                            <span class="text-xs font-semibold text-amber-700">${icon} ${label}</span>
                            <a href="${bookUrl}" class="mt-1 text-[10px] font-bold text-amber-600 hover:text-amber-800 underline">Pesan →</a>
                        </div>`;
                    }
                    return `<div class="avail-cell avail-cell--limited">
                        <span class="text-xs font-bold text-amber-700">⚠️ Terbatas</span>
                        <a href="${bookUrl}" class="mt-1 text-[10px] font-bold text-white bg-amber-500 hover:bg-amber-600 px-3 py-1 rounded-full transition-colors">Pesan</a>
                    </div>`;
                } else {
                    if (isMobile) {
                        return `<div class="avail-cell avail-cell--booked">
                            <span class="text-xs font-semibold text-red-600">${icon} ${label}</span>
                            <span class="text-[10px] text-red-500 mt-0.5">Penuh</span>
                        </div>`;
                    }
                    return `<div class="avail-cell avail-cell--booked">
                        <span class="text-xs font-bold text-red-600">❌ Penuh</span>
                    </div>`;
                }
            }

            function getSessionStatuses(bookedSessions, available) {
                const statuses = { pagi: 'available', siang: 'available', malam: 'available', fullboard: 'available' };

                if (!available) {
                    // Fully booked
                    return { pagi: 'booked', siang: 'booked', malam: 'booked', fullboard: 'booked' };
                }

                const hasFullboard = bookedSessions.some(s => s.includes('Fullboard'));
                if (hasFullboard) {
                    return { pagi: 'booked', siang: 'booked', malam: 'booked', fullboard: 'booked' };
                }

                const hasPagi = bookedSessions.some(s => s.includes('Pagi'));
                const hasSiang = bookedSessions.some(s => s.includes('Siang'));
                const hasMalam = bookedSessions.some(s => s.includes('Malam'));

                if (hasPagi) statuses.pagi = 'booked';
                if (hasSiang) { statuses.siang = 'booked'; statuses.malam = 'booked'; }
                if (hasMalam) { statuses.malam = 'booked'; statuses.siang = 'booked'; }

                // If any session is booked, Fullboard is not possible
                if (hasPagi || hasSiang || hasMalam) {
                    statuses.fullboard = 'booked';
                }

                // If there's 1 booking, mark remaining as limited
                if (bookedSessions.length === 1) {
                    for (const key of ['pagi', 'siang', 'malam', 'fullboard']) {
                        if (statuses[key] === 'available') {
                            statuses[key] = 'limited';
                        }
                    }
                }

                return statuses;
            }

            async function loadAvailability() {
                const date = dateInput.value;
                if (!date) return;

                for (const room of rooms) {
                    // Set loading state
                    const desktopRow = document.querySelector(`#avail-table-desktop tr[data-room-id="${room.id}"]`);
                    const mobileCard = document.querySelector(`#avail-cards-mobile [data-room-id="${room.id}"]`);

                    ['pagi', 'siang', 'malam', 'fullboard'].forEach(key => {
                        if (desktopRow) {
                            const cell = desktopRow.querySelector(`[data-session="${key}"]`);
                            if (cell) cell.innerHTML = '<div class="avail-cell avail-cell--loading"><span class="text-xs text-gray-400">...</span></div>';
                        }
                        if (mobileCard) {
                            const cell = mobileCard.querySelector(`[data-session="${key}"]`);
                            if (cell) cell.innerHTML = `<div class="avail-cell avail-cell--loading"><span class="text-xs text-gray-400">${SESSION_ICONS[key]} ${SESSION_LABELS[key]}</span><span class="text-[10px] text-gray-400">...</span></div>`;
                        }
                    });

                    try {
                        const res = await fetch(`/api/check-availability?room_id=${room.id}&date=${date}`);
                        const data = await res.json();
                        const statuses = getSessionStatuses(data.booked_sessions || [], data.available);

                        ['pagi', 'siang', 'malam', 'fullboard'].forEach(key => {
                            if (desktopRow) {
                                const cell = desktopRow.querySelector(`[data-session="${key}"]`);
                                if (cell) cell.innerHTML = renderCell(room.id, key, statuses[key], false);
                            }
                            if (mobileCard) {
                                const cell = mobileCard.querySelector(`[data-session="${key}"]`);
                                if (cell) cell.innerHTML = renderCell(room.id, key, statuses[key], true);
                            }
                        });
                    } catch (err) {
                        console.error('Error loading availability for room', room.id, err);
                    }
                }
            }

            dateInput.addEventListener('change', loadAvailability);

            // Load on page init
            loadAvailability();
        })();
    </script>
    @endpush
</x-app-layout>
