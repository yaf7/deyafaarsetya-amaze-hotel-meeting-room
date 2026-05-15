@extends('admin.layouts.app')

@section('page-title', 'Kelola Menu Buffet')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Kelola Menu Buffet</h1>
        <p class="text-gray-500 text-sm">Tambah, edit, atau hapus menu buffet</p>
    </div>
    <a href="{{ route('admin.buffet-menus.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 sm:px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm text-sm sm:text-base">
        <i class="fas fa-plus"></i>
        <span>Tambah Menu</span>
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 text-sm">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

<!-- Filter by Category -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4 mb-6">
    <div class="flex flex-wrap gap-2">
        <a href="{{ route('admin.buffet-menus.index') }}" 
           class="px-4 py-2 rounded-lg {{ !request('category') ? 'bg-amber-100 text-amber-700 font-semibold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors text-sm">
            Semua
        </a>
        <a href="{{ route('admin.buffet-menus.index', ['category' => 'soup']) }}" 
           class="px-4 py-2 rounded-lg {{ request('category') == 'soup' ? 'bg-amber-100 text-amber-700 font-semibold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors text-sm">
            Soup
        </a>
        <a href="{{ route('admin.buffet-menus.index', ['category' => 'mie']) }}" 
           class="px-4 py-2 rounded-lg {{ request('category') == 'mie' ? 'bg-amber-100 text-amber-700 font-semibold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors text-sm">
            Mie
        </a>
        <a href="{{ route('admin.buffet-menus.index', ['category' => 'ayam']) }}" 
           class="px-4 py-2 rounded-lg {{ request('category') == 'ayam' ? 'bg-amber-100 text-amber-700 font-semibold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors text-sm">
            Ayam
        </a>
        <a href="{{ route('admin.buffet-menus.index', ['category' => 'ikan']) }}" 
           class="px-4 py-2 rounded-lg {{ request('category') == 'ikan' ? 'bg-amber-100 text-amber-700 font-semibold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors text-sm">
            Ikan
        </a>
        <a href="{{ route('admin.buffet-menus.index', ['category' => 'sayuran']) }}" 
           class="px-4 py-2 rounded-lg {{ request('category') == 'sayuran' ? 'bg-amber-100 text-amber-700 font-semibold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors text-sm">
            Sayuran
        </a>
        <a href="{{ route('admin.buffet-menus.index', ['category' => 'fritter']) }}" 
           class="px-4 py-2 rounded-lg {{ request('category') == 'fritter' ? 'bg-amber-100 text-amber-700 font-semibold' : 'bg-gray-100 text-gray-600 hover:bg-gray-200' }} transition-colors text-sm">
            Fritter
        </a>
    </div>
</div>

@if($menus->count() > 0)
    <!-- Mobile: Cards -->
    <div class="block md:hidden space-y-4">
        @foreach($menus as $menu)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-utensils text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $menu->name }}</h3>
                            <p class="text-gray-500 text-xs uppercase">{{ ucfirst($menu->category) }}</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.buffet-menus.edit', $menu) }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                        <form action="{{ route('admin.buffet-menus.destroy', $menu) }}" method="POST">
                            @csrf
                            @method('DELETE')
                            <button type="submit"
                                    class="w-8 h-8 flex items-center justify-center rounded-lg bg-red-50 text-red-600"
                                    onclick="return confirm('Yakin hapus?')">
                                <i class="fas fa-trash text-sm"></i>
                            </button>
                        </form>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    <!-- Desktop: Table -->
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Menu</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Kategori</th>
                        <th class="py-4 px-6 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($menus as $menu)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-utensils text-green-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $menu->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="px-3 py-1 rounded-full text-xs font-semibold
                                    @if($menu->category == 'soup') bg-orange-100 text-orange-700
                                    @elseif($menu->category == 'mie') bg-yellow-100 text-yellow-700
                                    @elseif($menu->category == 'ayam') bg-red-100 text-red-700
                                    @elseif($menu->category == 'ikan') bg-blue-100 text-blue-700
                                    @elseif($menu->category == 'sayuran') bg-green-100 text-green-700
                                    @else bg-amber-100 text-amber-700
                                    @endif">
                                    {{ ucfirst($menu->category) }}
                                </span>
                            </td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.buffet-menus.edit', $menu) }}"
                                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.buffet-menus.destroy', $menu) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                                onclick="return confirm('Yakin hapus menu {{ $menu->name }}?')">
                                            <i class="fas fa-trash"></i>
                                        </button>
                                    </form>
                                </div>
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>

    <div class="mt-6">
        {{ $menus->appends(request()->query())->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-utensils text-gray-400 text-2xl sm:text-3xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Menu Buffet</h3>
        <p class="text-gray-500 mb-6 text-sm">Mulai tambahkan menu buffet pertama Anda</p>
        <a href="{{ route('admin.buffet-menus.create') }}"
           class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-medium transition-colors text-sm">
            <i class="fas fa-plus"></i>
            <span>Tambah Menu Pertama</span>
        </a>
    </div>
@endif
@endsection
