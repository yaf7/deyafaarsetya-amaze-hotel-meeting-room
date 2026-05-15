@extends('admin.layouts.app')

@section('page-title', 'Kelola Promosi')

@section('content')
<!-- Header -->
<div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4 mb-6">
    <div>
        <h1 class="text-2xl font-bold text-gray-800">Kelola Promosi</h1>
        <p class="text-gray-500 text-sm">Buat, edit, atau hapus promo diskon</p>
    </div>
    <a href="{{ route('admin.promotions.create') }}"
       class="inline-flex items-center justify-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-5 py-2.5 rounded-xl font-medium transition-colors shadow-sm">
        <i class="fas fa-plus"></i>
        <span>Tambah Promo</span>
    </a>
</div>

@if(session('success'))
    <div class="bg-green-50 border border-green-200 text-green-700 px-4 py-3 rounded-xl mb-6 flex items-center gap-3">
        <i class="fas fa-check-circle"></i>
        <span>{{ session('success') }}</span>
    </div>
@endif

@if($promotions->count() > 0)
    <!-- Cards Grid -->
    <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 gap-6">
        @foreach($promotions as $promo)
            <div class="bg-white rounded-2xl shadow-sm border border-gray-100 overflow-hidden hover:shadow-md transition-shadow">
                <!-- Promo Header -->
                <div class="bg-gradient-to-r from-amber-500 to-orange-500 px-5 py-4">
                    <div class="flex items-center justify-between">
                        <span class="text-white font-bold text-lg">-{{ number_format($promo->discount, 2) }}%</span>
                        @if($promo->status)
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-white/20 text-white">
                                <i class="fas fa-check mr-1"></i> Aktif
                            </span>
                        @else
                            <span class="inline-flex items-center px-2.5 py-1 rounded-full text-xs font-medium bg-red-500/80 text-white">
                                <i class="fas fa-times mr-1"></i> Nonaktif
                            </span>
                        @endif
                    </div>
                </div>
                
                <!-- Promo Info -->
                <div class="p-5">
                    <h3 class="font-bold text-lg text-gray-800 mb-3">{{ $promo->name }}</h3>
                    
                    <div class="flex items-center justify-between pt-4 border-t border-gray-100">
                        <div class="flex items-center gap-2">
                            <a href="{{ route('admin.promotions.edit', $promo) }}"
                               class="w-9 h-9 flex items-center justify-center rounded-lg bg-blue-50 text-blue-600 hover:bg-blue-100 transition-colors">
                                <i class="fas fa-edit"></i>
                            </a>
                            <form action="{{ route('admin.promotions.destroy', $promo) }}" method="POST" class="inline">
                                @csrf
                                @method('DELETE')
                                <button type="submit"
                                        class="w-9 h-9 flex items-center justify-center rounded-lg bg-red-50 text-red-600 hover:bg-red-100 transition-colors"
                                        onclick="return confirm('Yakin hapus promo {{ $promo->name }}?')">
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
        {{ $promotions->links() }}
    </div>
@else
    <!-- Empty State -->
    <div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-12 text-center">
        <div class="w-20 h-20 bg-gray-100 rounded-full flex items-center justify-center mx-auto mb-4">
            <i class="fas fa-tags text-gray-400 text-3xl"></i>
        </div>
        <h3 class="text-lg font-semibold text-gray-800 mb-2">Belum Ada Promo</h3>
        <p class="text-gray-500 mb-6">Buat promo untuk menarik pelanggan</p>
        <a href="{{ route('admin.promotions.create') }}"
           class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-medium transition-colors">
            <i class="fas fa-plus"></i>
            <span>Buat Promo Pertama</span>
        </a>
    </div>
@endif
@endsection
