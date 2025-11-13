@extends('layouts.app')

@section('title', 'Detail Produk')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('products.index') }}" 
           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            ← Kembali ke Daftar Produk
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Detail Produk</h1>
            <div class="space-x-2">
                <a href="{{ route('products.edit', $product) }}" 
                   class="inline-block px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition">
                    Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6 mb-6">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">ID</p>
                <p class="text-gray-900">{{ $product->id }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Nama Produk</p>
                <p class="text-gray-900 font-medium text-lg">{{ $product->name }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Kategori</p>
                @if($product->category)
                    <span class="inline-block px-3 py-1 bg-blue-100 text-blue-800 rounded-lg text-sm">
                        {{ $product->category->name }}
                    </span>
                @else
                    <p class="text-gray-400">-</p>
                @endif
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Harga</p>
                <p class="text-gray-900 font-bold text-lg">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
            </div>
        </div>

        @if($product->detail)
        <div class="border-t pt-6">
            <h2 class="text-lg font-semibold text-gray-900 mb-4">Informasi Detail</h2>
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Berat</p>
                    <p class="text-gray-900">{{ $product->detail->weight }} kg</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Ukuran</p>
                    <p class="text-gray-900">{{ $product->detail->size ?? '-' }}</p>
                </div>
                <div class="col-span-2">
                    <p class="text-sm font-medium text-gray-500 mb-1">Deskripsi</p>
                    <p class="text-gray-900">{{ $product->detail->description ?? '-' }}</p>
                </div>
            </div>
        </div>
        @endif

        <div class="border-t pt-6 mt-6">
            <div class="grid grid-cols-2 gap-6">
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Dibuat Pada</p>
                    <p class="text-gray-900">{{ $product->created_at->format('d M Y H:i') }}</p>
                </div>
                <div>
                    <p class="text-sm font-medium text-gray-500 mb-1">Diperbarui Pada</p>
                    <p class="text-gray-900">{{ $product->updated_at->format('d M Y H:i') }}</p>
                </div>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Stok di Gudang</h2>
        
        @if($product->warehouses->count() > 0)
        <div class="space-y-3">
            @foreach($product->warehouses as $warehouse)
            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">{{ $warehouse->name }}</p>
                    <p class="text-sm text-gray-600">{{ $warehouse->location ?? 'Lokasi tidak tersedia' }}</p>
                </div>
                <div class="text-right">
                    <p class="text-sm text-gray-500">Stok</p>
                    <p class="text-lg font-bold text-gray-900">{{ $warehouse->pivot->quantity }}</p>
                </div>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-4">Belum ada stok di gudang manapun.</p>
        @endif
    </div>
</div>
@endsection