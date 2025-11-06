<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title ?? 'Eksplor Jakarta' }} - Pariwisata Jakarta</title>
    
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link href="https://fonts.googleapis.com/css2?family=Plus+Jakarta+Sans:wght@300;400;500;600;700;800&display=swap" rel="stylesheet">
    
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            scroll-behavior: smooth;
        }
        body {
            font-family: 'Plus Jakarta Sans', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50 text-gray-800">
    
    <header class="bg-white shadow-sm sticky top-0 z-50">
        <nav class="container mx-auto px-4 py-4">
            <div class="flex items-center justify-between">

                <div class="flex items-center space-x-3">
                    <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                        <span class="text-white font-bold text-xl">J</span>
                    </div>
                    <div>
                        <h1 class="text-xl font-bold text-gray-900">Eksplor Jakarta</h1>
                        <p class="text-xs text-gray-500">Jelajahi Ibukota</p>
                    </div>
                </div>
                
                <div class="hidden md:flex items-center space-x-2">
                    <x-nav-link href="/" :active="request()->is('/')">Home</x-nav-link>
                    <x-nav-link href="/destinasi" :active="request()->is('destinasi')">Destinasi</x-nav-link>
                    <x-nav-link href="/kuliner" :active="request()->is('kuliner')">Kuliner</x-nav-link>
                    <x-nav-link href="/galeri" :active="request()->is('galeri')">Galeri</x-nav-link>
                    <x-nav-link href="/kontak" :active="request()->is('kontak')">Kontak</x-nav-link>
                </div>

                <button id="mobile-menu-btn" class="md:hidden p-2 rounded-lg hover:bg-gray-100">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16"/>
                    </svg>
                </button>
            </div>

            <div id="mobile-menu" class="hidden md:hidden mt-4 space-y-2">
                <x-nav-link href="/" :active="request()->is('/')" mobile="true">Home</x-nav-link>
                <x-nav-link href="/destinasi" :active="request()->is('destinasi')" mobile="true">Destinasi</x-nav-link>
                <x-nav-link href="/kuliner" :active="request()->is('kuliner')" mobile="true">Kuliner</x-nav-link>
                <x-nav-link href="/galeri" :active="request()->is('galeri')" mobile="true">Galeri</x-nav-link>
                <x-nav-link href="/kontak" :active="request()->is('kontak')" mobile="true">Kontak</x-nav-link>
            </div>
        </nav>
    </header>

    <main class="min-h-screen">
        @yield('content')
    </main>

    <footer class="bg-gray-900 text-gray-300 mt-20">
        <div class="container mx-auto px-4 py-12">
            <div class="grid grid-cols-1 md:grid-cols-3 gap-8">
                <div>
                    <div class="flex items-center space-x-3 mb-4">
                        <div class="w-10 h-10 bg-blue-600 rounded-full flex items-center justify-center">
                            <span class="text-white font-bold text-xl">J</span>
                        </div>
                        <h3 class="text-xl font-bold text-white">Eksplor Jakarta</h3>
                    </div>
                    <p class="text-sm text-gray-400">
                        Portal informasi pariwisata Jakarta yang menyajikan destinasi wisata, kuliner khas, dan galeri menarik dari ibukota Indonesia.
                    </p>
                </div>

                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Menu Cepat</h4>
                    <ul class="space-y-2 text-sm">
                        <li><a href="/" class="hover:text-blue-400 transition">Home</a></li>
                        <li><a href="/destinasi" class="hover:text-blue-400 transition">Destinasi Wisata</a></li>
                        <li><a href="/kuliner" class="hover:text-blue-400 transition">Kuliner Khas</a></li>
                        <li><a href="/galeri" class="hover:text-blue-400 transition">Galeri</a></li>
                        <li><a href="/kontak" class="hover:text-blue-400 transition">Kontak</a></li>
                    </ul>
                </div>

                <div>
                    <h4 class="text-lg font-semibold text-white mb-4">Kontak</h4>
                    <ul class="space-y-2 text-sm">
                        <li class="flex items-start space-x-2">
                            <span>📍</span>
                            <span>Jakarta, DKI Jakarta, Indonesia</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span>📧</span>
                            <span>info@eksplorjakarta.com</span>
                        </li>
                        <li class="flex items-start space-x-2">
                            <span>📞</span>
                            <span>+62 21 1234 5678</span>
                        </li>
                    </ul>
                </div>
            </div>

            <div class="border-t border-gray-800 mt-8 pt-8 text-center text-sm text-gray-500">
                <p>&copy; 2024 Eksplor Jakarta. All rights reserved.</p>
            </div>
        </div>
    </footer>

    <script>
        const mobileMenuBtn = document.getElementById('mobile-menu-btn');
        const mobileMenu = document.getElementById('mobile-menu');
        
        mobileMenuBtn.addEventListener('click', () => {
            mobileMenu.classList.toggle('hidden');
        });
    </script>

</body>
</html>