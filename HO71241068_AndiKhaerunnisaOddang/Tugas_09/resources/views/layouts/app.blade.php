<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'Sistem Manajemen Produk')</title>
    <script src="https://cdn.tailwindcss.com"></script>
    <style>
        * {
            font-family: 'Inter', -apple-system, BlinkMacSystemFont, 'Segoe UI', sans-serif;
        }
    </style>
</head>
<body class="bg-gray-50">
    <!-- Navbar -->
    <nav class="bg-white border-b border-gray-200">
        <div class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8">
            <div class="flex justify-between h-16">
                <div class="flex items-center">
                    <a href="{{ route('products.index') }}" class="text-xl font-bold text-gray-900">
                        Manajemen Produk
                    </a>
                </div>
                <div class="flex items-center space-x-1">
                    <a href="{{ route('categories.index') }}" 
                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('categories.*') ? 'bg-gray-100' : '' }}">
                        Kategori
                    </a>
                    <a href="{{ route('warehouses.index') }}" 
                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('warehouses.*') ? 'bg-gray-100' : '' }}">
                        Gudang
                    </a>
                    <a href="{{ route('products.index') }}" 
                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('products.*') ? 'bg-gray-100' : '' }}">
                        Produk
                    </a>
                    <a href="{{ route('stocks.index') }}" 
                    class="px-4 py-2 rounded-lg text-sm font-medium text-gray-700 hover:bg-gray-100 transition {{ request()->routeIs('stocks.*') ? 'bg-gray-100' : '' }}">
                        Stok
                    </a>
                </div>
            </div>
        </div>
    </nav>

    <!-- Main Content -->
    <main class="max-w-7xl mx-auto px-4 sm:px-6 lg:px-8 py-8">
        <!-- Alert Messages -->
        @if(session('success'))
        <div class="mb-6 bg-green-50 border border-green-200 text-green-800 px-4 py-3 rounded-lg" role="alert">
            <p class="font-medium">{{ session('success') }}</p>
        </div>
        @endif

        @if($errors->any())
        <div class="mb-6 bg-red-50 border border-red-200 text-red-800 px-4 py-3 rounded-lg" role="alert">
            <p class="font-medium">Terdapat kesalahan:</p>
            <ul class="mt-2 list-disc list-inside text-sm">
                @foreach($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
        @endif

        @yield('content')
    </main>
</body>
</html>