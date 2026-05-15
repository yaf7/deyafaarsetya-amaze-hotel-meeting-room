@extends('admin.layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Promo</h1>
        <a href="{{ route('admin.promotions.index') }}"
           class="text-gray-600 hover:text-gray-800">&larr; Kembali</a>
    </div>

    <form method="POST" action="{{ route('admin.promotions.update', $promotion) }}">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label class="block text-gray-700 mb-2 font-medium">Nama Promo *</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $promotion->name) }}"
                   class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="block text-gray-700 mb-2 font-medium">Persentase Diskon *</label>
            <div class="relative">
                <input type="number"
                       name="discount"
                       value="{{ old('discount', $promotion->discount) }}"
                       class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       min="0.01" max="100" step="0.01"
                       required>
                <span class="absolute right-3 top-2.5 text-gray-500">%</span>
            </div>
            @error('discount')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 mb-2 font-medium">Status *</label>
            <div class="flex items-center space-x-4">
                <label class="flex items-center">
                    <input type="radio" name="status" value="1"
                           class="text-blue-600 focus:ring-blue-500"
                           {{ old('status', $promotion->status) ? 'checked' : '' }}>
                    <span class="ml-2">Aktif</span>
                </label>
                <label class="flex items-center">
                    <input type="radio" name="status" value="0"
                           class="text-blue-600 focus:ring-blue-500"
                           {{ old('status', $promotion->status) == false ? 'checked' : '' }}>
                    <span class="ml-2">Nonaktif</span>
                </label>
            </div>
            @error('status')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-3 pt-4">
            <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.promotions.index') }}"
               class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2.5 rounded-lg text-center font-medium transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
