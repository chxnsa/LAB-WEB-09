@extends('layouts.app')

@section('title', 'Transfer Stok')

@section('content')
<div class="max-w-2xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('stocks.index') }}" 
           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            ← Kembali ke Manajemen Stok
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h1 class="text-2xl font-bold text-gray-900 mb-2">Transfer Stok</h1>
        <p class="text-sm text-gray-600 mb-6">
            Tambah atau kurangi stok produk di gudang. Gunakan nilai positif untuk menambah stok (+) dan nilai negatif untuk mengurangi stok (-).
        </p>

        <form action="{{ route('stocks.store') }}" method="POST">
            @csrf

            <div class="mb-4">
                <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Gudang <span class="text-red-500">*</span>
                </label>
                <select name="warehouse_id" 
                        id="warehouse_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Pilih Gudang</option>
                    @foreach($warehouses as $warehouse)
                        <option value="{{ $warehouse->id }}" {{ old('warehouse_id') == $warehouse->id ? 'selected' : '' }}>
                            {{ $warehouse->name }} - {{ $warehouse->location }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-4">
                <label for="product_id" class="block text-sm font-medium text-gray-700 mb-2">
                    Pilih Produk <span class="text-red-500">*</span>
                </label>
                <select name="product_id" 
                        id="product_id"
                        class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                        required>
                    <option value="">Pilih Produk</option>
                    @foreach($products as $product)
                        <option value="{{ $product->id }}" {{ old('product_id') == $product->id ? 'selected' : '' }}>
                            {{ $product->name }} - Rp {{ number_format($product->price, 0, ',', '.') }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="mb-6">
                <label for="quantity" class="block text-sm font-medium text-gray-700 mb-2">
                    Jumlah Stok <span class="text-red-500">*</span>
                </label>
                <input type="number" 
                       name="quantity" 
                       id="quantity" 
                       value="{{ old('quantity') }}"
                       placeholder="Contoh: +10 untuk menambah, -5 untuk mengurangi"
                       class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500"
                       required>
                <p class="mt-2 text-sm text-gray-500">
                    Masukkan nilai positif (+) untuk menambah stok atau nilai negatif (-) untuk mengurangi stok.
                </p>
            </div>

            <div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
                <h3 class="text-sm font-semibold text-blue-900 mb-2">Catatan Penting:</h3>
                <ul class="text-sm text-blue-800 space-y-1 list-disc list-inside">
                    <li>Stok tidak boleh menjadi negatif</li>
                    <li>Jika produk belum ada di gudang, stok awal dimulai dari 0</li>
                    <li>Pengurangan stok akan ditolak jika menghasilkan nilai negatif</li>
                </ul>
            </div>

            <div class="flex justify-end space-x-3">
                <a href="{{ route('stocks.index') }}" 
                   class="px-4 py-2 border border-gray-300 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-50 transition">
                    Batal
                </a>
                <button type="submit" 
                        class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg text-sm font-medium transition">
                    Proses Transfer
                </button>
            </div>
        </form>
    </div>
</div>
@endsection