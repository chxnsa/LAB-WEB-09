@extends('layouts.master')

@section('content')
<!-- Page Header -->
<section class="bg-blue-600 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Galeri Foto</h1>
            <p class="text-xl text-blue-100">
                Koleksi foto-foto indah dari berbagai sudut Jakarta yang memukau
            </p>
        </div>
    </div>
</section>

<!-- Gallery Grid -->
<section class="py-16 md:py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-3 gap-6 max-w-7xl mx-auto">
            @foreach($galeri as $item)
                <div class="group relative overflow-hidden rounded-2xl aspect-square bg-gray-200 shadow-sm hover:shadow-lg transition-all duration-300">
                    <img src="{{ $item['gambar'] }}" 
                        alt="{{ $item['caption'] }}" 
                        class="w-full h-full object-cover group-hover:scale-110 transition-transform duration-300">
                    <div class="absolute inset-0 bg-linear-to-t from-black/70 via-black/20 to-transparent opacity-0 group-hover:opacity-100 transition-opacity duration-300">
                        <div class="absolute bottom-0 left-0 right-0 p-6">
                            <h3 class="text-white font-semibold text-lg">{{ $item['caption'] }}</h3>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
</section>

<!-- Info Section -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl p-8 shadow-sm text-center">
            <div class="text-4xl mb-4">📸</div>
            <h3 class="text-2xl font-bold text-gray-900 mb-4">Bagikan Momen Anda</h3>
            <p class="text-gray-600 leading-relaxed mb-6">
                Punya foto menarik dari Jakarta? Bagikan pengalaman wisata Anda dengan hashtag 
                <span class="font-semibold text-blue-600">#EksplorJakarta</span> dan 
                <span class="font-semibold text-blue-600">#JakartaIndah</span>
            </p>
            <a href="/kontak" class="inline-block px-8 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition">
                Hubungi Kami
            </a>
        </div>
    </div>
</section>
@endsection