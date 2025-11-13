@extends('layouts.app')

@section('title', 'Daftar Produk')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Daftar Produk</h1>
    <a href="{{ route('products.create') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
        Tambah Produk
    </a>
</div>

<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    NO
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nama Produk
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Kategori
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Harga
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Aksi
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($products as $product)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $product->id }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ $product->name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    @if($product->category)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                            {{ $product->category->name }}
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 font-medium">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm space-x-2">
                    <a href="{{ route('products.show', $product) }}" 
                       class="text-blue-600 hover:text-blue-800 font-medium">
                        Lihat
                    </a>
                    <a href="{{ route('products.edit', $product) }}" 
                       class="text-yellow-600 hover:text-yellow-800 font-medium">
                        Edit
                    </a>
                    <form action="{{ route('products.destroy', $product) }}" 
                          method="POST" 
                          class="inline"
                          onsubmit="return confirm('Yakin ingin menghapus produk ini?')">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="text-red-600 hover:text-red-800 font-medium">
                            Hapus
                        </button>
                    </form>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="5" class="px-6 py-8 text-center text-gray-500">
                    Belum ada produk. Silakan tambah produk baru.
                </td>
            </tr>
            @endforelse
        </tbody>
    </table>
</div>
@endsection