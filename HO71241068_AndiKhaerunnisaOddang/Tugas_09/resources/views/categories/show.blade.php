@extends('layouts.app')

@section('title', 'Detail Kategori')

@section('content')
<div class="max-w-4xl mx-auto">
    <div class="mb-6">
        <a href="{{ route('categories.index') }}" 
           class="text-blue-600 hover:text-blue-800 text-sm font-medium">
            ← Kembali ke Daftar Kategori
        </a>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6 mb-6">
        <div class="flex justify-between items-start mb-6">
            <h1 class="text-2xl font-bold text-gray-900">Detail Kategori</h1>
            <div class="space-x-2">
                <a href="{{ route('categories.edit', $category) }}" 
                   class="inline-block px-4 py-2 bg-yellow-500 hover:bg-yellow-600 text-white rounded-lg text-sm font-medium transition">
                    Edit
                </a>
            </div>
        </div>

        <div class="grid grid-cols-2 gap-6">
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">ID</p>
                <p class="text-gray-900">{{ $category->id }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Nama Kategori</p>
                <p class="text-gray-900 font-medium">{{ $category->name }}</p>
            </div>
            <div class="col-span-2">
                <p class="text-sm font-medium text-gray-500 mb-1">Deskripsi</p>
                <p class="text-gray-900">{{ $category->description ?? '-' }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Dibuat Pada</p>
                <p class="text-gray-900">{{ $category->created_at->format('d M Y H:i') }}</p>
            </div>
            <div>
                <p class="text-sm font-medium text-gray-500 mb-1">Diperbarui Pada</p>
                <p class="text-gray-900">{{ $category->updated_at->format('d M Y H:i') }}</p>
            </div>
        </div>
    </div>

    <div class="bg-white rounded-lg shadow-sm border border-gray-200 p-6">
        <h2 class="text-lg font-bold text-gray-900 mb-4">Produk dalam Kategori Ini</h2>
        
        @if($category->products->count() > 0)
        <div class="space-y-3">
            @foreach($category->products as $product)
            <div class="flex justify-between items-center p-4 bg-gray-50 rounded-lg">
                <div>
                    <p class="font-medium text-gray-900">{{ $product->name }}</p>
                    <p class="text-sm text-gray-600">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                </div>
                <a href="{{ route('products.show', $product) }}" 
                   class="text-blue-600 hover:text-blue-800 text-sm font-medium">
                    Lihat Detail
                </a>
            </div>
            @endforeach
        </div>
        @else
        <p class="text-gray-500 text-center py-4">Belum ada produk dalam kategori ini.</p>
        @endif
    </div>
</div>
@endsection