@extends('admin.layouts.app')

@section('page-title', 'Tambah Ruang Meeting')

@section('content')
<!-- Header -->
<div class="mb-6">
    <a href="{{ route('admin.rooms.index') }}" class="inline-flex items-center gap-2 text-gray-500 hover:text-gray-700 mb-2">
        <i class="fas fa-arrow-left"></i>
        <span>Kembali</span>
    </a>
    <h1 class="text-2xl font-bold text-gray-800">Tambah Ruang Meeting Baru</h1>
</div>

<!-- Form Card -->
<div class="bg-white rounded-2xl shadow-sm border border-gray-100 p-6 max-w-3xl">
    <form method="POST" action="{{ route('admin.rooms.store') }}" enctype="multipart/form-data">
        @csrf

        <div class="space-y-6">
            <!-- Nama Ruang -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-door-open text-amber-500 mr-2"></i>Nama Ruang <span class="text-red-500">*</span>
                </label>
                <input type="text" name="name" 
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 outline-none transition-colors"
                       placeholder="Contoh: Ballroom A"
                       required>
            </div>

            <!-- Kapasitas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-users text-amber-500 mr-2"></i>Kapasitas Maksimal <span class="text-red-500">*</span>
                </label>
                <input type="number" name="capacity" 
                       class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 outline-none transition-colors"
                       placeholder="Jumlah orang"
                       min="1" required>
            </div>

            <!-- Fasilitas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-list-check text-amber-500 mr-2"></i>Fasilitas <span class="text-red-500">*</span>
                </label>
                <textarea name="facilities" rows="3" 
                          class="w-full px-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 outline-none transition-colors resize-none"
                          placeholder="Proyektor, AC, Sound System, dll"
                          required></textarea>
            </div>

            <!-- Layout Kapasitas -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-3">
                    <i class="fas fa-th-large text-amber-500 mr-2"></i>Kapasitas per Layout <span class="text-red-500">*</span>
                </label>
                <div class="grid grid-cols-2 md:grid-cols-4 gap-4">
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label class="block text-xs text-gray-500 mb-1">Theater</label>
                        <input type="number" name="theater" 
                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-amber-500 outline-none"
                               min="0" required>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label class="block text-xs text-gray-500 mb-1">Classroom</label>
                        <input type="number" name="classroom" 
                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-amber-500 outline-none"
                               min="0" required>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label class="block text-xs text-gray-500 mb-1">Round Table</label>
                        <input type="number" name="round_table" 
                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-amber-500 outline-none"
                               min="0" required>
                    </div>
                    <div class="bg-gray-50 rounded-xl p-4">
                        <label class="block text-xs text-gray-500 mb-1">U-Shape</label>
                        <input type="number" name="u_shape" 
                               class="w-full px-3 py-2 border-2 border-gray-200 rounded-lg focus:border-amber-500 outline-none"
                               min="0" required>
                    </div>
                </div>
            </div>


            <!-- Harga -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-tag text-amber-500 mr-2"></i>Harga <span class="text-red-500">*</span>
                </label>
                <div class="relative">
                    <span class="absolute left-4 top-1/2 -translate-y-1/2 text-gray-500">Rp</span>
                    <input type="number" name="price" 
                           value="0"
                           class="w-full pl-12 pr-4 py-3 border-2 border-gray-200 rounded-xl focus:border-amber-500 focus:ring-0 outline-none transition-colors"
                           placeholder="0"
                           min="0" required>
                </div>
                <p class="text-xs text-gray-500 mt-1">Harga sewa ruangan</p>
            </div>

            <!-- Foto -->
            <div>
                <label class="block text-sm font-medium text-gray-700 mb-2">
                    <i class="fas fa-image text-amber-500 mr-2"></i>Foto Ruang (Opsional)
                </label>
                <input type="file" name="photo" accept="image/*" 
                       class="w-full px-4 py-3 border-2 border-dashed border-gray-300 rounded-xl focus:border-amber-500 outline-none transition-colors file:mr-4 file:py-2 file:px-4 file:rounded-lg file:border-0 file:bg-amber-50 file:text-amber-700 file:font-medium hover:file:bg-amber-100">
            </div>
        </div>

        <!-- Buttons -->
        <div class="flex items-center gap-3 mt-8 pt-6 border-t border-gray-100">
            <button type="submit" 
                    class="inline-flex items-center gap-2 bg-amber-500 hover:bg-amber-600 text-white px-6 py-3 rounded-xl font-medium transition-colors">
                <i class="fas fa-save"></i>
                <span>Simpan</span>
            </button>
            <a href="{{ route('admin.rooms.index') }}" 
               class="inline-flex items-center gap-2 bg-gray-100 hover:bg-gray-200 text-gray-700 px-6 py-3 rounded-xl font-medium transition-colors">
                <span>Batal</span>
            </a>
        </div>
    </form>
</div>
@endsection
