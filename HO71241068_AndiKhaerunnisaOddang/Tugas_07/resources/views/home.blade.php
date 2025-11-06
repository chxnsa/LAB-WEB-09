@extends('layouts.master')

@section('content')
<section class="relative text-white py-20 md:py-32 min-h-[600px] flex items-center">
    <div class="absolute inset-0 bg-cover bg-center bg-no-repeat" 
        style="background-image: url('/images/hero-jakarta.jpg');">
    </div>
    
    <div class="absolute inset-0 bg-gradient-to-r from-blue-900/90 via-blue-800/80 to-transparent"></div>
    
    <div class="container mx-auto px-4 relative z-10">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-6xl font-bold mb-6 leading-tight">
                Selamat Datang di<br>{{ $kota }}
            </h1>
            <p class="text-xl md:text-2xl text-blue-100 mb-8">
                {{ $tagline }}
            </p>
            <p class="text-lg text-blue-50 mb-8 leading-relaxed">
                Jelajahi pesona ibukota Indonesia dengan segala keunikan, modernitas, dan kekayaan budayanya. 
                Dari landmark ikonik hingga kuliner legendaris yang menggugah selera.
            </p>
            <div class="flex flex-wrap gap-4">
                <a href="/destinasi" class="px-8 py-3 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition shadow-lg">
                    Jelajahi Destinasi
                </a>
                <a href="/kuliner" class="px-8 py-3 border-2 border-white text-white font-semibold rounded-xl hover:bg-white hover:text-blue-600 transition">
                    Cicipi Kuliner
                </a>
            </div>
        </div>
    </div>
</section>

<!-- About Jakarta -->
<section class="py-16 md:py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-4xl mx-auto text-center">
            <h2 class="text-3xl md:text-4xl font-bold text-gray-900 mb-6">Tentang Jakarta</h2>
            <p class="text-lg text-gray-600 leading-relaxed mb-6">
                Jakarta adalah ibukota dan kota terbesar di Indonesia. Sebagai pusat pemerintahan, ekonomi, dan budaya, 
                Jakarta menawarkan perpaduan unik antara kehidupan urban modern dengan warisan budaya Betawi yang kaya.
            </p>
            <p class="text-lg text-gray-600 leading-relaxed">
                Dengan populasi lebih dari 10 juta jiwa, Jakarta merupakan kota metropolitan yang dinamis, 
                penuh dengan gedung pencakar langit, pusat perbelanjaan megah, museum bersejarah, 
                serta berbagai destinasi wisata dan kuliner yang tak terhitung jumlahnya.
            </p>
        </div>
    </div>
</section>

<!-- Features -->
<section class="py-16 bg-white">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
            <!-- Feature 1 -->
            <div class="text-center p-8 rounded-2xl bg-gray-50">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">🏛️</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Destinasi Bersejarah</h3>
                <p class="text-gray-600">
                    Jelajahi berbagai tempat bersejarah dari era kolonial hingga modern yang menjadi saksi bisu perjalanan bangsa.
                </p>
            </div>

            <!-- Feature 2 -->
            <div class="text-center p-8 rounded-2xl bg-gray-50">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">🍜</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Kuliner Legendaris</h3>
                <p class="text-gray-600">
                    Nikmati kelezatan kuliner khas Betawi yang autentik dan beragam pilihan makanan dari seluruh Nusantara.
                </p>
            </div>

            <!-- Feature 3 -->
            <div class="text-center p-8 rounded-2xl bg-gray-50">
                <div class="w-16 h-16 bg-blue-100 rounded-full flex items-center justify-center mx-auto mb-4">
                    <span class="text-3xl">🌆</span>
                </div>
                <h3 class="text-xl font-bold text-gray-900 mb-3">Kehidupan Urban</h3>
                <p class="text-gray-600">
                    Rasakan dinamika kota metropolitan dengan gedung pencakar langit, pusat bisnis, dan hiburan kelas dunia.
                </p>
            </div>
        </div>
    </div>
</section>

<!-- Statistics -->
<section class="py-16 bg-gray-50">
    <div class="container mx-auto px-4">
        <div class="grid grid-cols-2 md:grid-cols-4 gap-8">
            <div class="text-center">
                <div class="text-4xl font-bold text-blue-600 mb-2">10M+</div>
                <div class="text-gray-600">Penduduk</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-blue-600 mb-2">100+</div>
                <div class="text-gray-600">Destinasi Wisata</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-blue-600 mb-2">50+</div>
                <div class="text-gray-600">Kuliner Khas</div>
            </div>
            <div class="text-center">
                <div class="text-4xl font-bold text-blue-600 mb-2">5</div>
                <div class="text-gray-600">Kotamadya</div>
            </div>
        </div>
    </div>
</section>

<!-- CTA Section -->
<section class="py-16 md:py-20">
    <div class="container mx-auto px-4">
        <div class="bg-blue-600 rounded-3xl p-12 text-center text-white">
            <h2 class="text-3xl md:text-4xl font-bold mb-4">Siap Menjelajahi Jakarta?</h2>
            <p class="text-xl text-blue-100 mb-8 max-w-2xl mx-auto">
                Temukan pengalaman tak terlupakan di setiap sudut ibukota. Mulai petualangan Anda sekarang!
            </p>
            <a href="/destinasi" class="inline-block px-8 py-4 bg-white text-blue-600 font-semibold rounded-xl hover:bg-blue-50 transition">
                Mulai Jelajahi
            </a>
        </div>
    </div>
</section>
@endsection