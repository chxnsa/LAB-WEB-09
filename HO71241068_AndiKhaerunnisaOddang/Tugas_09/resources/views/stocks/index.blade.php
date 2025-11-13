@extends('layouts.app')

@section('title', 'Manajemen Stok')

@section('content')
<div class="flex justify-between items-center mb-6">
    <h1 class="text-2xl font-bold text-gray-900">Manajemen Stok</h1>
    <a href="{{ route('stocks.transfer') }}" 
       class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-lg text-sm font-medium transition">
        Transfer Stok
    </a>
</div>

<!-- Filter Gudang -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 p-4 mb-6">
    <form action="{{ route('stocks.index') }}" method="GET" class="flex items-end gap-4">
        <div class="flex-1">
            <label for="warehouse_id" class="block text-sm font-medium text-gray-700 mb-2">
                Pilih Gudang
            </label>
            <select name="warehouse_id" 
                    id="warehouse_id"
                    onchange="this.form.submit()"
                    class="w-full px-4 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-blue-500 focus:border-blue-500">
                @foreach($warehouses as $warehouse)
                    <option value="{{ $warehouse->id }}" 
                        {{ $selectedWarehouse && $selectedWarehouse->id == $warehouse->id ? 'selected' : '' }}>
                        {{ $warehouse->name }} - {{ $warehouse->location }}
                    </option>
                @endforeach
            </select>
        </div>
    </form>
</div>

@if($selectedWarehouse)
<!-- Info Gudang -->
<div class="bg-blue-50 border border-blue-200 rounded-lg p-4 mb-6">
    <div class="flex items-start">
        <div class="flex-1">
            <h2 class="font-semibold text-blue-900">{{ $selectedWarehouse->name }}</h2>
            <p class="text-sm text-blue-700">{{ $selectedWarehouse->location ?? 'Lokasi tidak tersedia' }}</p>
        </div>
        <div class="text-right">
            <p class="text-sm text-blue-700">Total Produk</p>
            <p class="text-2xl font-bold text-blue-900">{{ $stocks->count() }}</p>
        </div>
    </div>
</div>

<!-- Tabel Stok -->
<div class="bg-white rounded-lg shadow-sm border border-gray-200 overflow-hidden">
    <table class="min-w-full divide-y divide-gray-200">
        <thead class="bg-gray-50">
            <tr>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    No
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Nama Produk
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Kategori
                </th>
                <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">
                    Jumlah Stok
                </th>
            </tr>
        </thead>
        <tbody class="bg-white divide-y divide-gray-200">
            @forelse($stocks as $index => $stock)
            <tr class="hover:bg-gray-50">
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900">
                    {{ $index + 1 }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm font-medium text-gray-900">
                    {{ $stock->product_name }}
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-600">
                    @if($stock->category_name)
                        <span class="px-2 py-1 bg-blue-100 text-blue-800 rounded text-xs">
                            {{ $stock->category_name }}
                        </span>
                    @else
                        <span class="text-gray-400">-</span>
                    @endif
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="px-3 py-1 bg-green-100 text-green-800 rounded-lg font-semibold">
                        {{ $stock->quantity }}
                    </span>
                </td>
            </tr>
            @empty
            <tr>
                <td colspan="4" class="px-6 py-8 text-center text-gray-500">
                    Belum ada stok di gudang ini.
                </td>
            </tr>
            @endforelse
        </tbody>
        @if($stocks->count() > 0)
        <tfoot class="bg-gray-50 border-t border-gray-200">
            <tr>
                <td colspan="3" class="px-6 py-4 text-sm font-bold text-gray-900">
                    Total Stok
                </td>
                <td class="px-6 py-4 whitespace-nowrap text-sm">
                    <span class="px-3 py-1 bg-blue-100 text-blue-800 rounded-lg font-bold">
                        {{ $stocks->sum('quantity') }}
                    </span>
                </td>
            </tr>
        </tfoot>
        @endif
    </table>
</div>
@else
<div class="bg-yellow-50 border border-yellow-200 rounded-lg p-6 text-center">
    <p class="text-yellow-800">Belum ada gudang. Silakan tambah gudang terlebih dahulu.</p>
</div>
@endif
@endsection