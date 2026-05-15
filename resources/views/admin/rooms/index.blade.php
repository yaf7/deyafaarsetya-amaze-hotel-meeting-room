@extends('admin.layouts.app')

@section('page-title', 'Kelola Ruang Meeting')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Ruang Meeting</h1>
        <p class="text-gray-500 text-sm">Tambah, edit, atau hapus ruang meeting</p>
    </div>
    <a href="{{ route('admin.rooms.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm">
        <i class="fas fa-plus"></i>
        <span>Tambah Ruang</span>
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if($rooms->count() > 0)
    <!-- Cards Grid for Rooms -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($rooms as $room)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <!-- Room Image -->
                <div class="relative h-40 bg-gray-100">
                    @if($room->photo)
                        <img src="{{ asset('storage/' . $room->photo) }}"
                             alt="{{ $room->name }}"
                             class="w-full h-full object-cover">
                    @else
                        <div class="w-full h-full flex items-center justify-center">
                            <i class="fas fa-image text-gray-300 text-4xl"></i>
                        </div>
                    @endif
                    <div class="absolute top-3 right-3">
                        <span class="inline-flex items-center px-2.5 py-1 rounded-lg text-xs font-medium bg-white/90 text-gray-700 backdrop-blur-sm">
                            <i class="fas fa-users mr-1 text-amber-500"></i>
                            {{ $room->capacity }} orang
                        </span>
                    </div>
                </div>
                
                <!-- Room Info -->
                <div class="p-5">
                    <h3 class="font-bold text-lg text-gray-800 mb-2">{{ $room->name }}</h3>
                    <p class="text-gray-500 text-sm mb-4 line-clamp-2">{{ $room->facilities }}</p>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <span class="font-bold text-amber-600">Rp{{ number_format($room->price, 0, ',', '.') }}</span>
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.rooms.edit', $room) }}"
                               class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.rooms.destroy', $room) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                        onclick="return confirm('Yakin ingin menghapus ruang {{ $room->name }}?')">
                                    <i class="fas fa-trash"></i>
                                </button>
                            </form>
                        </div>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <div class="mt-6">
        {{ $rooms->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-door-open text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Ruang Meeting</h3>
        <p class="text-gray-500 mb-6">Mulai tambahkan ruang meeting pertama Anda</p>
        <a href="{{ route('admin.rooms.create') }}"
           class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <i class="fas fa-plus"></i>
            <span>Tambah Ruang Pertama</span>
        </a>
    </div>
@endif
@endsection
