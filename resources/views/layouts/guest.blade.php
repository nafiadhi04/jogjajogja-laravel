<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name', 'JogjaJogja') }}</title>

    {{-- Tailwind CSS (CDN) --}}
    <script src="https://cdn.tailwindcss.com"></script>

    {{-- Alpine.js (CDN) --}}
    <script src="https://cdn.jsdelivr.net/npm/alpinejs@3.x.x/dist/cdn.min.js" defer></script>

    {{-- Tailwind Plugins (Typography, Aspect Ratio) --}}
    <script src="https://cdn.tailwindcss.com?plugins=typography,aspect-ratio" defer></script>

    {{-- Font Inter --}}
    <link rel="stylesheet" href="https://rsms.me/inter/inter.css">

    <style>
        /* ... CSS Anda yang sudah ada ... */
        body {
            font-family: 'Inter', sans-serif;
            margin: 0;
            padding: 0;
            overflow-x: hidden;
            /* Memastikan scrollbar vertikal selalu ada, mencegah pergeseran lateral (samping) */
            overflow-y: scroll;
        }

        .prose img {
            margin-top: 0;
            margin-bottom: 0;
        }

        a:visited {
            color: inherit;
        }

        .breadcrumb a:visited {
            color: #0d9488;
        }

        /* --- CSS BARU UNTUK ANIMASI LOADER --- */
        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-30px); /* Puncak pantulan */
            }
            60% {
                transform: translateY(-15px); /* Pantulan kedua */
            }
        }

        .logo-bounce {
            /* Terapkan animasi: nama, durasi, timing function, infinite */
            animation: bounce 1s ease-in-out infinite;
        }
    </style>

    {{-- Stack untuk CSS kustom halaman --}}
    @stack('styles')
</head>

<body class="font-sans antialiased text-gray-900 min-h-screen flex flex-col" x-data="{ loading: true }" x-init="
    // Sembunyikan preloader setelah seluruh aset halaman dimuat PLUS JEDA 2 DETIK
    window.addEventListener('load', () => {
        setTimeout(() => {
            loading = false;
        }, 500); // ✨ Jeda 3000 milidetik (2 detik)
    });

    // Tampilkan preloader saat navigasi dimulai (opsional, untuk efek transisi)
    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            // Hanya aktifkan untuk tautan internal biasa
            if (link.getAttribute('href') && link.getAttribute('href').startsWith('/') && !link.hasAttribute('target')) {
                loading = true;
            }
        });
    });
">

    {{-- 1. PRELOADER / SPLASH SCREEN (Elemen pertama di dalam BODY) --}}
    <div x-show="loading" 
        x-transition:leave="transition ease-in duration-300" 
        x-transition:leave-start="opacity-100" 
        x-transition:leave-end="opacity-0" 
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-white">
        
        {{-- Menerapkan class CSS kustom untuk animasi memantul --}}
        <img src="{{ asset('images/loader.webp') }}" 
            alt="Loading Logo Jogja Jogja" 
            class="h-20 w-auto logo-bounce"> 
    </div>
    {{-- END PRELOADER --}}

    {{-- 2. KONTEN UTAMA DIMULAI DI SINI --}}

    {{-- Navigasi Publik --}}
    <nav class="bg-white shadow-md" x-data="{ open: false }">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="flex items-center justify-between h-16">

                {{-- Logo (Kiri) untuk Header --}}
                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="flex items-center text-2xl font-bold text-gray-800 hover:text-gray-900 transition">
                        
                        {{-- Logo Header (menggunakan jogjajogja-blue-long.webp) --}}
                        <div class="w-22 h-7 mr-2"> 
                            <img src="{{ asset('images/jogjajogja-blue-long.webp') }}" alt="Logo Header Jogja Jogja" class="w-full h-full">
                        </div>
                    </a>
                </div>

                {{-- Link Menu Utama (Tengah, Hanya Desktop) --}}
                <div class="hidden md:flex flex-grow justify-center">
                    <div class="flex items-baseline space-x-6">
                        
                        @php
                            // ... (variabel route Anda)
                            $penginapanRoute = route('penginapan.list') ?? url('/penginapan'); 
                            $wisataRoute = route('wisata.list') ?? url('/wisata');
                            $isPenginapanActive = request()->routeIs('penginapan.*');
                            $isWisataActive = request()->routeIs('wisata.*');
                        @endphp

                        <a href="{{ $penginapanRoute }}"
                            class="px-3 py-2 text-sm font-medium rounded-md transition
                            {{ $isPenginapanActive ? 'text-teal-600 bg-teal-50 font-semibold' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-600' }}">
                            Penginapan
                        </a>
                        <a href="{{ $wisataRoute }}"
                            class="px-3 py-2 text-sm font-medium rounded-md transition
                            {{ $isWisataActive ? 'text-teal-600 bg-teal-50 font-semibold' : 'text-gray-700 hover:bg-teal-50 hover:text-teal-600' }}">
                            Wisata
                        </a>
                        {{-- Tambahkan link menu desktop lainnya di sini --}}
                    </div>
                </div>

                {{-- Tombol Login/Dashboard & Menu Button (Kanan) --}}
                <div class="flex items-center space-x-2 md:space-x-4">
                    {{-- Tombol Login/Dashboard (Desktop) --}}
                    <div class="hidden md:flex items-center">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-3 py-2 text-sm font-medium text-white bg-teal-600 rounded-lg hover:bg-teal-700 transition">Dashboard</a>
                        @else
                            <a href="{{ route('login') ?? url('/login') }}"
                                class="px-4 py-2 text-sm font-semibold text-white bg-orange-500 rounded-lg hover:bg-orange-600 transition duration-150 shadow-md">
                                Log in
                            </a>
                        @endauth
                    </div>

                    {{-- Mobile menu button (Tampil di semua ukuran layar) --}}
                    <div class="flex items-center">
                        <button @click="open = !open" type="button" class="inline-flex items-center justify-center p-2 rounded-md text-gray-400 hover:text-teal-600 hover:bg-gray-100 focus:outline-none focus:ring-2 focus:ring-inset focus:ring-teal-500 transition" aria-controls="mobile-menu" aria-expanded="false">
                            <span class="sr-only">Open main menu</span>
                            <svg x-show="!open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 6h16M4 12h16M4 18h16" />
                            </svg>
                            <svg x-show="open" class="block h-6 w-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" aria-hidden="true">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12" />
                            </svg>
                        </button>
                    </div>
                </div>
            </div>
        </div>
        
        {{-- OVERLAY SEMITRANSPARAN (Z-40) --}}
        <div x-show="open" @click="open = false" 
            x-transition:enter="transition ease-in-out duration-300"
            x-transition:enter-start="opacity-0"
            x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in-out duration-300"
            x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0"
            class="fixed inset-0 bg-black bg-opacity-40 z-40">
        </div>

        {{-- DRAWER MENU (Z-50) --}}
        <div x-show="open"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 h-full bg-white shadow-xl z-50 overflow-y-auto w-80">

            {{-- **KONTEN MOBILE (LENGKAP)** - Tampil di Mobile, Hilang di Desktop --}}
            <div class="md:hidden">
                <div class="flex justify-between items-center px-4 py-4 mb-2">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 mr-1 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                        <span class="text-2xl font-bold text-teal-600">jogja jogja</span>
                    </a>
                    <button @click="open = false" class="p-2 text-gray-500 hover:text-gray-900 rounded-full hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                    </button>
                </div>

                {{-- Search Bar --}}
                <div class="px-4 pb-4">
                    <div class="relative">
                        <input type="search" placeholder="Cari artikel ..." class="w-full py-2 pl-4 pr-10 text-base border border-gray-300 rounded-lg focus:ring-teal-600 focus:border-teal-600 bg-gray-100" />
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-teal-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path></svg>
                        </button>
                    </div>
                </div>

                {{-- Menu Utama (Mobile - Sesuai Gambar 1) --}}
                <div class="divide-y divide-gray-100 border-t border-b border-gray-100">
                    <a href="{{ url('/') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">BERANDA</a>
                    <a href="{{ url('/penginapan') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">PENGINAPAN</a>
                    <a href="{{ url('/kuliner') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">KULINER</a>
                    <a href="{{ url('/wisata') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">WISATA</a>
                    <a href="{{ url('/event') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">EVENT</a>
                    <a href="{{ url('/lifestyle') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">LIFESTYLE</a>
                    <a href="{{ url('/artikel') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">ARTIKEL</a>
                </div>

                {{-- Menu Informasi & Kemitraan (Mobile - Sesuai Gambar 2) --}}
                <div class="divide-y divide-gray-100 border-b border-gray-100">
                    <a href="{{ url('/tentang-kami') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path></svg>
                        TENTANG KAMI
                    </a>
                    <a href="{{ url('/kemitraan') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path></svg>
                        KEMITRAAN
                    </a>
                    <a href="{{ url('/pasang-iklan') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15.536 8.464l-4.243 4.243-1.061-1.061M12 21V3m0 18a9 9 0 009-9h-9m0 0V3m0 0V3a9 9 0 00-9 9h9"></path></svg>
                        PASANG IKLAN
                    </a>
                    <a href="{{ url('/syarat-ketentuan') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path></svg>
                        SYARAT & KETENTUAN
                    </a>
                    <a href="{{ url('/hubungi-kami') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                        HUBUNGI KAMI
                    </a>
                    <a href="{{ route('login') ?? url('/login') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path></svg>
                        LOGIN
                    </a>
                </div>

                {{-- Social Media Icons --}}
                <div class="flex justify-center space-x-4 mt-6 pt-4 border-t border-gray-100">
                    <a href="{{ url('/instagram') }}" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:text-teal-600">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">...</svg>
                    </a>
                    <a href="{{ url('/tiktok') }}" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:text-teal-600">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">...</svg>
                    </a>
                    <a href="{{ url('/youtube') }}" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:hover:text-teal-600">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">...</svg>
                    </a>
                </div>
            </div>

            {{-- **KONTEN DESKTOP (HANYA KEMITRAAN/KONTAK)** - Tampil di Desktop, Hilang di Mobile --}}
            <div class="hidden md:block">
                <div class="px-4 py-4">
                    <div class="flex justify-between items-center pb-4 mb-4 border-b">
                        <a href="{{ url('/') }}" class="flex items-center space-x-2">
                            <svg class="w-8 h-8 mr-1 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round"><path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path><polyline points="9 22 9 12 15 12 15 22"></polyline></svg>
                            <span class="text-xl font-bold text-teal-600">jogjajogja.id</span>
                        </a>
                        <button @click="open = false" class="p-2 text-gray-500 hover:text-gray-900 rounded-full hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path></svg>
                        </button>
                    </div>

                    {{-- Kemitraan --}}
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Kemitraan</h3>
                        <div class="space-y-3">
                            <a href="{{ route('login') ?? url('/login/mitra') }}" class="flex items-center text-base text-gray-700 hover:text-teal-600 transition">
                                <svg class="w-5 h-5 mr-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path></svg>
                                Login Mitra
                            </a>
                            <a href="{{ route('register') ?? url('/register/mitra') }}" class="flex items-center text-base text-gray-700 hover:text-teal-600 transition">
                                <svg class="w-5 h-5 mr-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path></svg>
                                Pendaftaran Mitra
                            </a>
                            <a href="{{ url('/kemitraan') }}" class="block text-base text-gray-700 hover:text-teal-600 transition ml-8">Kemitraan</a>
                            <a href="{{ url('/pasang-iklan') }}" class="block text-base text-gray-700 hover:text-teal-600 transition ml-8">Pasang Iklan</a>
                            <a href="{{ url('/syarat-ketentuan') }}" class="block text-base text-gray-700 hover:text-teal-600 transition ml-8">Syarat dan Ketentuan</a>
                            <a href="{{ url('/hubungi-kami') }}" class="block text-base text-gray-700 hover:text-teal-600 transition ml-8">Hubungi Kami</a>
                        </div>
                    </div>

                    {{-- Pasang Iklan (Kontak) --}}
                    <div class="mb-6 border-t pt-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Pasang Iklan</h3>
                        <a href="tel:0895392337544" class="flex items-center text-base text-gray-700 hover:text-teal-600">
                            <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                            0895-3923-37544
                        </a>
                    </div>

                    {{-- Kontak Kami --}}
                    <div class="mb-6 border-t pt-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Kontak Kami</h3>
                        <div class="space-y-3">
                            <a href="mailto:info@jogjajogja.id" class="flex items-center text-base text-gray-700 hover:text-teal-600">
                                <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.848 5.232a2 2 0 002.304 0L21 8m-2-3H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2z"></path></svg>
                                info@jogjajogja.id
                            </a>
                            <a href="mailto:cs@jogjajogja.id" class="flex items-center text-base text-gray-700 hover:text-teal-600">
                                <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.848 5.232a2 2 0 002.304 0L21 8m-2-3H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2z"></path></svg>
                                cs@jogjajogja.id
                            </a>
                            <a href="tel:0895392337544" class="flex items-center text-base text-gray-700 hover:text-teal-600">
                                <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24"><path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path></svg>
                                0895-3923-37544
                            </a>
                        </div>
                    </div>

                    {{-- Social Media Icons --}}
                    <div class="flex space-x-4 mt-6 pt-4 border-t border-gray-100">
                        <a href="{{ url('/instagram') }}" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:text-teal-600">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">...</svg>
                        </a>
                        <a href="{{ url('/tiktok') }}" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:text-teal-600">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">...</svg>
                        </a>
                        <a href="{{ url('/youtube') }}" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:hover:text-teal-600">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">...</svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- Konten Halaman Utama --}}
    <main class="flex-grow bg-gray-50">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-footer />

    {{-- Stack untuk JS kustom halaman --}}
    @stack('scripts')
</body>

</html>