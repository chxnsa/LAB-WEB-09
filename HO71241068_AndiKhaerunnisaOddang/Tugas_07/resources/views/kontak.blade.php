@extends('layouts.master')

@section('content')
<!-- Page Header -->
<section class="bg-blue-600 text-white py-16">
    <div class="container mx-auto px-4">
        <div class="max-w-3xl">
            <h1 class="text-4xl md:text-5xl font-bold mb-4">Hubungi Kami</h1>
            <p class="text-xl text-blue-100">
                Ada pertanyaan atau saran? Kami siap membantu Anda
            </p>
        </div>
    </div>
</section>

<!-- Contact Section -->
<section class="py-16 md:py-20">
    <div class="container mx-auto px-4">
        <div class="max-w-5xl mx-auto">
            <div class="grid grid-cols-1 lg:grid-cols-2 gap-12">
                
                <!-- Contact Info -->
                <div>
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Informasi Kontak</h2>
                    <div class="space-y-6">
                        <!-- Address -->
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                                <span class="text-2xl">📍</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Alamat</h3>
                                <p class="text-gray-600">
                                    Jl. Thamrin No. 1<br>
                                    Jakarta Pusat, DKI Jakarta 10310<br>
                                    Indonesia
                                </p>
                            </div>
                        </div>

                        <!-- Email -->
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                                <span class="text-2xl">📧</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Email</h3>
                                <p class="text-gray-600">info@eksplorjakarta.com</p>
                                <p class="text-gray-600">support@eksplorjakarta.com</p>
                            </div>
                        </div>

                        <!-- Phone -->
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                                <span class="text-2xl">📞</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Telepon</h3>
                                <p class="text-gray-600">+62 21 1234 5678</p>
                                <p class="text-gray-600">+62 812 3456 7890</p>
                            </div>
                        </div>

                        <!-- Hours -->
                        <div class="flex items-start space-x-4">
                            <div class="w-12 h-12 bg-blue-100 rounded-xl flex items-center justify-center shrink-0">
                                <span class="text-2xl">🕐</span>
                            </div>
                            <div>
                                <h3 class="font-semibold text-gray-900 mb-1">Jam Operasional</h3>
                                <p class="text-gray-600">Senin - Jumat: 09.00 - 17.00 WIB</p>
                                <p class="text-gray-600">Sabtu: 09.00 - 14.00 WIB</p>
                                <p class="text-gray-600">Minggu & Libur: Tutup</p>
                            </div>
                        </div>
                    </div>
                </div>

                <!-- Contact Form -->
                <div class="bg-white rounded-2xl shadow-sm p-8">
                    <h2 class="text-2xl font-bold text-gray-900 mb-6">Kirim Pesan</h2>
                    <form class="space-y-6">
                        <!-- Name -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nama Lengkap
                            </label>
                            <input type="text" 
                                placeholder="Masukkan nama Anda"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Email -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Email
                            </label>
                            <input type="email" 
                                placeholder="nama@email.com"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Phone -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Nomor Telepon
                            </label>
                            <input type="tel" 
                                placeholder="+62 812 3456 7890"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Subject -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Subjek
                            </label>
                            <input type="text" 
                                placeholder="Topik pesan Anda"
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent">
                        </div>

                        <!-- Message -->
                        <div>
                            <label class="block text-sm font-semibold text-gray-700 mb-2">
                                Pesan
                            </label>
                            <textarea rows="5" 
                                placeholder="Tulis pesan Anda di sini..."
                                class="w-full px-4 py-3 rounded-xl border border-gray-300 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:border-transparent resize-none"></textarea>
                        </div>

                        <!-- Submit Button -->
                        <button type="submit" 
                                class="w-full px-6 py-3 bg-blue-600 text-white font-semibold rounded-xl hover:bg-blue-700 transition">
                            Kirim Pesan
                        </button>

                        <p class="text-sm text-gray-500 text-center">
                            Kami akan merespon pesan Anda dalam 1-2 hari kerja
                        </p>
                    </form>
                </div>

            </div>
        </div>
    </div>
</section>


@endsection