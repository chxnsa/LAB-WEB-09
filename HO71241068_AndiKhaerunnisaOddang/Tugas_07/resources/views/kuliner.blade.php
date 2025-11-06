@extends('layouts.master')

@section('content')
<!-- Page Header -->
<section class="bg-blue-600 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Kuliner Khas Jakarta</h1>
            <p class="text-xl text-blue-100">
                Nikmati cita rasa autentik kuliner Betawi yang menggugah selera
            </p>
        </div>
    </div>
</section>

<!-- Kuliner Grid -->
<section class="py-16 md:py-20">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-2 gap-8 max-w-6xl mx-auto">
            @foreach($kuliner as $item)
                <x-card 
                    :title="$item['nama']" 
                    :description="$item['deskripsi']"
                    :image="$item['gambar']"
                />
            @endforeach
        </div>
    </div>
</section>

<!-- Fun Fact -->
<section class="py-12 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto">
            <div class="bg-white rounded-2xl p-8 shadow-sm mb-6">
                <div class="flex items-start space-x-4">
                    <div class="text-3xl">🍴</div>
                    <div>
                        <h3 class="text-xl font-bold text-gray-900 mb-2">Tahukah Anda?</h3>
                        <p class="text-gray-600 leading-relaxed">
                            Kerak Telor merupakan makanan khas Betawi yang sudah ada sejak abad ke-19 dan menjadi salah satu ikon kuliner Jakarta yang legendaris. 
                            Biasanya dijual di acara-acara besar seperti perayaan Monas atau Jakarta Fair.
                        </p>
                    </div>
                </div>
            </div>

            <div class="bg-blue-600 text-white rounded-2xl p-8 shadow-sm">
                <h3 class="text-xl font-bold mb-2">Rekomendasi Tempat</h3>
                <p class="text-blue-100 leading-relaxed">
                    Untuk mencicipi kuliner khas Betawi yang autentik, Anda bisa mengunjungi kawasan Kota Tua, 
                    Setu Babakan (kampung Betawi), atau berbagai festival kuliner yang sering diadakan di Jakarta.
                </p>
            </div>
        </div>
    </div>
</section>
@endsection