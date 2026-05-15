@extends('admin.layouts.app')

@section('content')
<div class="bg-white rounded-xl shadow p-6 max-w-2xl mx-auto">
    <div class="flex justify-between items-center mb-6">
        <h1 class="text-2xl font-bold text-gray-800">Edit Paket Makanan</h1>
        <a href="{{ route('admin.packages.index') }}"
           class="text-gray-600 hover:text-gray-800">&larr; Kembali</a>
    </div>

    <form method="POST" action="{{ route('admin.packages.update', $package) }}">
        @csrf
        @method('PUT')

        <div class="mb-5">
            <label class="block text-gray-700 mb-2 font-medium">Nama Paket *</label>
            <input type="text"
                   name="name"
                   value="{{ old('name', $package->name) }}"
                   class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                   required>
            @error('name')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-5">
            <label class="block text-gray-700 mb-2 font-medium">Harga per Pax *</label>
            <div class="relative">
                <span class="absolute left-3 top-2.5 text-gray-500">Rp</span>
                <input type="number"
                       name="price"
                       value="{{ old('price', $package->price) }}"
                       class="w-full border rounded-lg pl-10 pr-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                       min="0"
                       required>
            </div>
            @error('price')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="mb-6">
            <label class="block text-gray-700 mb-2 font-medium">Deskripsi *</label>
            <textarea name="description"
                      class="w-full border rounded-lg px-4 py-2 focus:ring-2 focus:ring-blue-500 focus:outline-none"
                      rows="4"
                      required>{{ old('description', $package->description) }}</textarea>
            @error('description')
                <p class="text-red-500 text-sm mt-1">{{ $message }}</p>
            @enderror
        </div>

        <div class="flex space-x-3 pt-4">
            <button type="submit"
                    class="flex-1 bg-blue-600 hover:bg-blue-700 text-white py-2.5 rounded-lg font-medium transition">
                Simpan Perubahan
            </button>
            <a href="{{ route('admin.packages.index') }}"
               class="flex-1 bg-gray-500 hover:bg-gray-600 text-white py-2.5 rounded-lg text-center font-medium transition">
                Batal
            </a>
        </div>
    </form>
</div>
@endsection
