@extends('admin.layouts.app')

@section('page-title', 'Edit Menu Buffet')

@section('content')
<!-- Header -->
<div class="mb-6">
    <div class="flex items-center gap-3 mb-2">
        <a href="{{ route('admin.buffet-menus.index') }}" 
           class="w-8 h-8 flex items-center justify-center rounded-lg bg-gray-100 hover:bg-gray-200 text-gray-600 transition-colors">
            <i class="fas fa-arrow-left text-sm"></i>
        </a>
        <div>
            <h1 class="text-xl sm:text-2xl font-bold text-gray-800">Edit Menu Buffet</h1>
            <p class="text-gray-500 text-sm">Perbarui infor masi menu buffet</p>
        </div>
    </div>
</div>

<!-- Form -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6">
    <form action="{{ route('admin.buffet-menus.update', $buffetMenu) }}" method="POST">
        @csrf
        @method('PUT')

        <div class="space-y-6">
            <!-- Nama Menu -->
            <div>
                <label for="name" class="block text-sm font-semibold text-gray-700 mb-2">
                    Nama Menu <span class="text-red-500">*</span>
                </label>
                <input type="text" 
                       name="name" 
                       id="name" 
                       value="{{ old('name', $buffetMenu->name) }}"
                       class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all @error('name') border-red-300 @enderror"
                       required>
                @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Kategori -->
            <div>
                <label for="category" class="block text-sm font-semibold text-gray-700 mb-2">
                    Kategori <span class="text-red-500">*</span>
                </label>
                <select name="category" 
                        id="category" 
                        class="w-full px-4 py-3 rounded-xl border border-gray-200 focus:outline-none focus:ring-2 focus:ring-amber-500 focus:border-transparent transition-all @error('category') border-red-300 @enderror"
                        required>
                    <option value="">-- Pilih Kategori --</option>
                    <option value="soup" {{ old('category', $buffetMenu->category) == 'soup' ? 'selected' : '' }}>Soup</option>
                    <option value="mie" {{ old('category', $buffetMenu->category) == 'mie' ? 'selected' : '' }}>Mie</option>
                    <option value="ayam" {{ old('category', $buffetMenu->category) == 'ayam' ? 'selected' : '' }}>Ayam</option>
                    <option value="ikan" {{ old('category', $buffetMenu->category) == 'ikan' ? 'selected' : '' }}>Ikan</option>
                    <option value="sayuran" {{ old('category', $buffetMenu->category) == 'sayuran' ? 'selected' : '' }}>Sayuran</option>
                    <option value="fritter" {{ old('category', $buffetMenu->category) == 'fritter' ? 'selected' : '' }}>Fritter</option>
                </select>
                @error('category')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <!-- Action Buttons -->
            <div class="flex gap-3 pt-4">
                <button type="submit"
                        class="flex-1 sm:flex-none bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                    <i class="fas fa-save mr-2"></i>
                    Simpan Perubahan
                </button>
                <a href="{{ route('admin.buffet-menus.index') }}"
                   class="flex-1 sm:flex-none bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium transition-colors text-center">
                    Batal
                </a>
            </div>
        </div>
    </form>
</div>
@endsection
