<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'JogjaJogja') }}</title>
    {{-- Tailwind CSS --}}
    <script src="https://cdn.tailwindcss.com"></script>
    {{-- Alpine.js --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js"></script>
    {{-- Tailwind Typography (untuk styling deskripsi) --}}
    <script src="https://cdn.tailwindcss.com?plugins=typography,aspect-ratio"></script>
    {{-- Font Inter --}}
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">
    <style>
        body {
            font-family: 'Inter', sans-serif;
        }

        .prose img {
            margin-top: 0;
            margin-bottom: 0;
        }
    </style>
</head>

<body class="font-sans antialiased text-gray-900">
    <div class="min-h-screen bg-gray-50">
        {{-- Navigasi Publik --}}
        <nav class="bg-white shadow-sm">
            <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">
                <div class="flex items-center justify-between h-16">
                    {{-- Logo --}}
                    <div class="flex-shrink-0">
                        <a href="{{ url('/') }}" class="text-2xl font-bold text-indigo-600">
                            JogjaJogja
                        </a>
                    </div>
                    {{-- Link Menu --}}
                    <div class="hidden md:block">
                        <div class="flex items-baseline ml-10 space-x-4">
                            <a href="{{ route('penginapan.list') }}"
                                class="px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100">Penginapan</a>
                            {{-- Tambahkan link untuk Wisata di sini nanti --}}
                            {{-- <a href="#"
                                class="px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100">Wisata</a>
                            --}}
                        </div>
                    </div>
                    {{-- Tombol Login/Dashboard --}}
                    <div class="hidden md:block">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-3 py-2 text-sm font-medium text-white bg-indigo-600 rounded-md hover:bg-indigo-700">Dashboard</a>
                        @else
                            <a href="{{ route('login') }}"
                                class="px-3 py-2 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100">Log in</a>
                            @if (Route::has('register'))
                                <a href="{{ route('register') }}"
                                    class="px-3 py-2 ml-4 text-sm font-medium text-gray-700 rounded-md hover:bg-gray-100">Register</a>
                            @endif
                        @endauth
                    </div>
                </div>
            </div>
        </nav>

        {{-- Konten Halaman Utama --}}
        <main>
            {{ $slot }}
        </main>
    </div>
</body>

</html>