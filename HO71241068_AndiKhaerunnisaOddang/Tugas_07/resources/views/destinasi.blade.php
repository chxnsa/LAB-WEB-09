@extends('layouts.master')

@section('content')
<!-- Page Header -->
<section class="bg-blue-600 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Destinasi Wisata</h1>
            <p class="text-xl text-blue-100">
                Jelajahi tempat-tempat menarik dan ikonik di Jakarta yang wajib Anda kunjungi
            </p>
        </div>
    </div>
</section>

<!-- Destinasi Grid -->
<section class="py-16 md:py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">
            @foreach($destinasi as $item)
                <x-card 
                    :title="$item['nama']" 
                    :description="$item['deskripsi']"
                    :image="$item['gambar']"
                />
            @endforeach
        </div>
    </div>
</section>

<!-- Info Banner -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto bg-white rounded-2xl p-8 shadow-sm">
            <div class="flex items-start space-x-4">
                <div class="text-3xl">💡</div>
                <div>
                    <h3 class="text-xl font-bold text-gray-900 mb-2">Tips Berwisata</h3>
                    <p class="text-gray-600 leading-relaxed">
                        Kunjungi destinasi wisata di pagi atau sore hari untuk menghindari terik matahari dan keramaian. 
                        Jangan lupa membawa kamera untuk mengabadikan momen berharga Anda!
                    </p>
                </div>
            </div>
        </div>
    </div>
</section>
@endsection