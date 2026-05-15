@extends('admin.layouts.app')

@section('page-title', 'Tambah Paket Makanan')

@section('content')
<!-- Header -->
<div class="mb-6">
    <a href="{{ route('admin.packages.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 mb-2">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali</span>
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Tambah Paket Makanan Baru</h1>
</div>

<!-- Form Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-2xl">
    <form method="POST" action="{{ route('admin.packages.store') }}">
        @csrf

        <div class="space-y-6">
            <!-- Nama Paket -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-utensils text-amber-500 mr-2"></i>Nama Paket <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" value="{{ old('name') }}"
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 outline-none transition-colors"
                       placeholder="Contoh: Half Day Meeting"
                       required>
                @error('name')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Harga -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag text-amber-500 mr-2"></i>Harga per Pax <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" name="price" value="{{ old('price') }}"
                           class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 outline-none transition-colors"
                           placeholder="150000"
                           min="0" required>
                </div>
                @error('price')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>

            <!-- Deskripsi -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-align-left text-amber-500 mr-2"></i>Deskripsi <span class="text-red-500">*</span>
                </label>
                <textarea name="description" rows="4"
                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 outline-none transition-colors resize-none"
                          placeholder="Contoh: 4 jam: 1 Coffee Break + 1 Meal"
                          required>{{ old('description') }}</textarea>
                <p class="text-xs text-gray-500 mt-2">
                    <i class="fas fa-info-circle mr-1"></i>
                    Format: [durasi] + [fasilitas]. Contoh: "8 jam: 2 Coffee Break + 2 Meal + 1 malam menginap"
                </p>
                @error('description')
                    <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
            <button type="submit" 
                    class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                <i class="fas fa-save"></i>
                <span>Simpan Paket</span>
            </button>
            <a href="{{ route('admin.packages.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium transition-colors">
                <span>Batal</span>
            </a>
        </div>
    </form>
</div>
@endsection
