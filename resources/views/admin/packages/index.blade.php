@extends('admin.layouts.app')

@section('page-title', 'Kelola Paket Makanan')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Kelola Paket Makanan</h1>
        <p class="text-gray-500 text-sm">Tambah, edit, atau hapus paket catering</p>
    </div>
    <a href="{{ route('admin.packages.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-4 sm:px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm text-sm sm:text-base">
        <i class="fas fa-plus"></i>
        <span>Tambah Paket</span>
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3 text-sm">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if($packages->count() > 0)
    <!-- Mobile: Cards -->
    <div class="block md:hidden space-y-4">
        @foreach($packages as $pkg)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-4">
                <div class="flex items-start justify-between gap-3">
                    <div class="flex items-center gap-3">
                        <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center flex-shrink-0">
                            <i class="fas fa-utensils text-green-600"></i>
                        </div>
                        <div>
                            <h3 class="font-semibold text-gray-800">{{ $pkg->name }}</h3>
                            <p class="text-amber-600 font-bold text-sm">Rp{{ number_format($pkg->price, 0, ',', '.') }}/pax</p>
                        </div>
                    </div>
                    <div class="flex items-center gap-2">
                        <a href="{{ route('admin.packages.edit', $pkg) }}"
                           class="w-8 h-8 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600">
                            <i class="fas fa-edit text-sm"></i>
                        </a>
                        <form action="{{ route('admin.packages.destroy', $pkg) }}" method="POST">
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
                <p class="text-gray-500 text-sm mt-3">{{ Str::limit($pkg->description, 80) }}</p>
            </div>
        @endforeach
    </div>

    <!-- Desktop: Table -->
    <div class="hidden md:block bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 border-b border-gray-100">
                    <tr>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Nama Paket</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Harga/Pax</th>
                        <th class="py-4 px-6 text-left text-xs font-semibold text-gray-500 uppercase tracking-wider">Deskripsi</th>
                        <th class="py-4 px-6 text-right text-xs font-semibold text-gray-500 uppercase tracking-wider">Aksi</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-100">
                    @foreach($packages as $pkg)
                        <tr class="hover:bg-gray-50 transition-colors">
                            <td class="py-4 px-6">
                                <div class="flex items-center gap-3">
                                    <div class="w-10 h-10 bg-green-100 rounded-xl flex items-center justify-center">
                                        <i class="fas fa-utensils text-green-600"></i>
                                    </div>
                                    <span class="font-medium text-gray-800">{{ $pkg->name }}</span>
                                </div>
                            </td>
                            <td class="py-4 px-6">
                                <span class="font-bold text-amber-600">Rp{{ number_format($pkg->price, 0, ',', '.') }}</span>
                                <span class="text-gray-400 text-sm">/pax</span>
                            </td>
                            <td class="py-4 px-6 text-gray-500 text-sm">{{ Str::limit($pkg->description, 50) }}</td>
                            <td class="py-4 px-6">
                                <div class="flex items-center justify-end gap-2">
                                    <a href="{{ route('admin.packages.edit', $pkg) }}"
                                       class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                        <i class="fas fa-edit"></i>
                                    </a>
                                    <form action="{{ route('admin.packages.destroy', $pkg) }}" method="POST" class="inline">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit"
                                                class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                                onclick="return confirm('Yakin hapus paket {{ $pkg->name }}?')">
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
        {{ $packages->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-8 sm:p-12 text-center">
        <div class="w-16 h-16 sm:w-20 sm:h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-utensils text-gray-400 text-2xl sm:text-3xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Paket Makanan</h3>
        <p class="text-gray-500 mb-6 text-sm">Mulai tambahkan paket catering pertama Anda</p>
        <a href="{{ route('admin.packages.create') }}"
           class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-medium transition-colors text-sm">
            <i class="fas fa-plus"></i>
            <span>Tambah Paket Pertama</span>
        </a>
    </div>
@endif
@endsection
