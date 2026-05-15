@extends('admin.layouts.app')

@section('page-title', 'Tambah Promo')

@section('content')
<!-- Header -->
<div class="mb-6">
    <a href="{{ route('admin.promotions.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 mb-2">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali</span>
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Tambah Promo Baru</h1>
</div>

<!-- Form Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.promotions.store') }}">
        @csrf

        <div class="space-y-6">
            <!-- Nama Promo -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tags text-amber-500 mr-2"></i>Nama Promo <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 outline-none transition-colors"
                       placeholder="Contoh: Diskon Lebaran"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-percent text-amber-500 mr-2"></i>Persentase Diskon <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <input type="number" name="discount" value="{{ old('discount') }}"
                           class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 outline-none transition-colors"
                           placeholder="50"
                           min="0.01" max="100" step="0.01" required>
                    <span class="absolute right-4 top-1/2 -translate-y-1/2 text-gray-500">%</span>
                </div>
                <p class="text-xs text-gray-500 mt-2">Masukkan persentase diskon (0.01% - 100%)</p>
                @error('discount')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Status -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-toggle-on text-amber-500 mr-2"></i>Status <span class="text-red-500">*</span>
                </label>
                <div class="flex items-center gap-6">
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="status" value="1"
                               class="w-5 h-5 text-amber-500 focus:ring-amber-500"
                               {{ old('status', true) ? 'checked' : '' }}>
                        <span class="text-gray-700">Aktif</span>
                    </label>
                    <label class="flex items-center gap-3 cursor-pointer">
                        <input type="radio" name="status" value="0"
                               class="w-5 h-5 text-amber-500 focus:ring-amber-500"
                               {{ old('status') == '0' ? 'checked' : '' }}>
                        <span class="text-gray-700">Nonaktif</span>
                    </label>
                </div>
                @error('status')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
            <button type="submit" 
                    class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                <i class="fas fa-save"></i>
                <span>Simpan Promo</span>
            </button>
            <a href="{{ route('admin.promotions.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium transition-colors">
                <span>Batal</span>
            </a>
        </div>
    </form>
</div>
@endsection
