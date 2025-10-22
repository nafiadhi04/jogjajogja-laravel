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
            overflow-y: scroll;
        }

        /* --- CSS BARU UNTUK TRANSISI TEKS DAN SHADOW PADA TULISAN PUTIH --- */
        .text-shadow-sm {
            text-shadow: 0 1px 2px rgba(0, 0, 0, 0.4);
        }

        /* Tambahkan transisi warna untuk elemen menu agar lebih halus */
        .menu-link-transition {
            transition: color 300ms ease, background-color 300ms ease, border-color 300ms ease;
        }

        @keyframes bounce {
            0%, 20%, 50%, 80%, 100% {
                transform: translateY(0);
            }
            40% {
                transform: translateY(-30px);
            }
            60% {
                transform: translateY(-15px);
            }
        }

        .logo-bounce {
            animation: bounce 1s ease-in-out infinite;
        }

        /* CSS KHUSUS UNTUK EFEK HEADER TARIK */
        .header-transition {
            transition: all 300ms ease-in-out; 
        }

        /* Class tersembunyi (digeser ke atas) */
        .header-hidden {
            transform: translateY(-100%);
        }
    </style>

    {{-- Stack untuk CSS kustom halaman --}}
    @stack('styles')
</head>

<body class="font-sans antialiased text-gray-900 min-h-screen flex flex-col" x-data="{ 
    loading: true, 
    isScrolled: false, 
    isHeaderVisible: true, // Untuk menyembunyikan/menampilkan header
    lastScrollY: 0, // Posisi scroll sebelumnya
    // ✅ MODIFIKASI: Deteksi halaman detail (route harus sesuai)
    isDetailPage: {{ request()->routeIs('penginapan.detail') || request()->routeIs('wisata.detail') ? 'true' : 'false' }},
}" 
x-init="
    // Logika loading
    window.addEventListener('load', () => {
        setTimeout(() => {
            loading = false;
        }, 1500);
    });

    document.querySelectorAll('a').forEach(link => {
        link.addEventListener('click', () => {
            if (link.getAttribute('href') && link.getAttribute('href').startsWith('/') && !link.hasAttribute('target')) {
                loading = true;
            }
        });
    });

    // MODIFIKASI: Logika Scroll Dinamis (Scroll Down SHOW, Scroll Up HIDE)
    window.addEventListener('scroll', () => {
        const currentScrollY = window.scrollY;

        // 1. Logika perubahan warna/shadow header
        isScrolled = currentScrollY > 50;
        
        // 2. Logika Sembunyikan/Tampilkan header
        if (currentScrollY > 150) { // Hanya aktif jika sudah melewati 150px
            if (currentScrollY > lastScrollY) {
                // Scrolling down (Tampilkan header)
                isHeaderVisible = true;
            } else {
                // Scrolling up (Tampilkan header)
                isHeaderVisible = true;
            }
        } else {
            // Selalu tampilkan header di area atas (di bawah 150px)
            isHeaderVisible = true;
        }
        
        // Simpan posisi scroll saat ini
        lastScrollY = currentScrollY;
    }, { passive: true });
">

    {{-- 1. PRELOADER --}}
    <div x-show="loading"
        x-transition:leave="transition ease-in duration-300"
        x-transition:leave-start="opacity-100"
        x-transition:leave-end="opacity-0"
        class="fixed inset-0 z-[9999] flex items-center justify-center bg-white">
        <img src="{{ asset('images/loader.webp') }}"
            alt="Loading Logo Jogja Jogja"
            class="h-20 w-auto logo-bounce">
    </div>

    {{-- 2. KONTEN UTAMA --}}

    {{-- Navigasi Publik (HEADER FIXED dengan TRANSISI) --}}
    <nav class="fixed top-0 left-0 w-full z-30 transition-all duration-300 ease-in-out"
        x-bind:class="{
            // ✅ MODIFIKASI: Header Gelap di Halaman Detail saat di atas
            'bg-gray-800 shadow-md': isDetailPage && !isScrolled, 

            // Putih Padat dengan Shadow saat Scrolled (berlaku untuk semua halaman)
            'bg-white shadow-md': isScrolled,
            
            // Transparan saat di Atas (Hanya berlaku di halaman non-detail, sekarang termasuk pengecualian isDetailPage)
            'bg-transparent shadow-none': !isScrolled && !isDetailPage, 
        }"
        
        x-data="{ open: false }">
        <div class="px-4 mx-auto max-w-7xl sm:px-6 lg:px-8">

            <div class="flex items-center justify-between h-16">

                {{-- Logo (Kiri) untuk Header --}}
                <div class="flex-shrink-0">
                    <a href="{{ url('/') }}" class="flex items-center text-2xl font-bold hover:text-gray-900 transition">

                        {{-- Warna Logo menyesuaikan Scroll --}}
                        <div class="w-22 h-7 mr-2 transition-colors duration-300"
                            x-bind:class="{ 'text-gray-800': isScrolled, 'text-white': !isScrolled }">
                            <img src="{{ asset('images/jogjajogja-blue-long.webp') }}" alt="Logo Header Jogja Jogja" class="w-full h-full">
                        </div>
                    </a>
                </div>

                {{-- Link Menu Utama (Tengah, Hanya Desktop) --}}
                <div class="hidden md:flex flex-grow justify-center">
                    <div class="flex items-baseline space-x-6">

                        @php
                            $homeRoute = url('/');
                            $berandaRoute = route('beranda');
                            $penginapanRoute = route('penginapan.list') ?? url('/penginapan');
                            $wisataRoute = route('wisata.list') ?? url('/wisata');

                            $isHomeActive = request()->is('/');
                            $isBerandaActive = request()->routeIs('beranda');
                            $isPenginapanActive = request()->routeIs('penginapan.*');
                            $isWisataActive = request()->routeIs('wisata.*');
                        @endphp

                        <a href="{{ $berandaRoute }}"
                            class="px-3 py-2 text-sm font-semibold rounded-md menu-link-transition"
                            x-bind:class="{
                                // SCROLLED (HEADER PUTIH, TEKS GELAP)
                                'text-teal-600 bg-teal-50': isScrolled && {{ $isBerandaActive ? 'true' : 'false' }},
                                'text-gray-700 hover:bg-teal-50 hover:text-teal-600': isScrolled && {{ $isBerandaActive ? 'false' : 'true' }},

                                // TIDAK SCROLLED (HEADER TRANSPARAN, TEKS PUTIH)
                                'text-white text-shadow-sm hover:bg-white/20 hover:text-white': !isScrolled && {{ $isBerandaActive ? 'false' : 'true' }},
                                'text-white border border-white bg-white/10 text-shadow-sm': !isScrolled && {{ $isBerandaActive ? 'true' : 'false' }},
                            }">
                            Beranda
                        </a>

                        <a href="{{ $penginapanRoute }}"
                            class="px-3 py-2 text-sm font-semibold rounded-md menu-link-transition"
                            x-bind:class="{
                                // SCROLLED (HEADER PUTIH, TEKS GELAP)
                                'text-teal-600 bg-teal-50': isScrolled && {{ $isPenginapanActive ? 'true' : 'false' }},
                                'text-gray-700 hover:bg-teal-50 hover:text-teal-600': isScrolled && {{ $isPenginapanActive ? 'false' : 'true' }},

                                // TIDAK SCROLLED (HEADER TRANSPARAN, TEKS PUTIH)
                                'text-white text-shadow-sm hover:bg-white/20 hover:text-white': !isScrolled && {{ $isPenginapanActive ? 'false' : 'true' }},
                                'text-white border border-white bg-white/10 text-shadow-sm': !isScrolled && {{ $isPenginapanActive ? 'true' : 'false' }}
                            }">
                            Penginapan
                        </a>

                        <a href="{{ $wisataRoute }}"
                            class="px-3 py-2 text-sm font-semibold rounded-md menu-link-transition"
                            x-bind:class="{
                                // SCROLLED (HEADER PUTIH, TEKS GELAP)
                                'text-teal-600 bg-teal-50': isScrolled && {{ $isWisataActive ? 'true' : 'false' }},
                                'text-gray-700 hover:bg-teal-50 hover:text-teal-600': isScrolled && {{ $isWisataActive ? 'false' : 'true' }},

                                // TIDAK SCROLLED (HEADER TRANSPARAN, TEKS PUTIH)
                                'text-white text-shadow-sm hover:bg-white/20 hover:text-white': !isScrolled && {{ $isWisataActive ? 'false' : 'true' }},
                                'text-white border border-white bg-white/10 text-shadow-sm': !isScrolled && {{ $isWisataActive ? 'true' : 'false' }}
                            }">
                            Wisata
                        </a>
                        {{-- Tambahkan link menu desktop lainnya di sini --}}
                    </div>
                </div>

                {{-- Tombol Login/Dashboard & Menu Button (Kanan) --}}
                <div class="flex items-center space-x-2">

                    {{-- MODIFIKASI: Tombol Login dengan outline --}}
                    <div class="hidden md:flex items-center">
                        @auth
                            <a href="{{ url('/dashboard') }}"
                                class="px-4 py-2 text-sm font-semibold text-white bg-teal-600 rounded-full hover:bg-teal-700 transition shadow-md">Dashboard</a>
                        @else
                            {{-- Teks dan border login juga berubah warna --}}
                            <a href="{{ route('login') ?? url('/login') }}"
                                class="flex items-center px-4 py-2 text-sm font-semibold bg-orange-500 rounded-full transition duration-300 shadow-md"
                                x-bind:class="{
                                    // TEKS PUTIH DAN BORDER PUTIH SAAT TRANSPARAN
                                    'text-white border-2 border-white hover:bg-orange-600': !isScrolled,
                                    // TEKS PUTIH DAN BORDER ORANGE SAAT SCROLLED
                                    'text-white border-2 border-orange-500 hover:bg-orange-600': isScrolled
                                }">

                                {{-- Ikon User/Orang --}}
                                <svg xmlns="http://www.w3.org/2000/svg" class="h-5 w-5 mr-1" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2"
                                    x-bind:class="{ 'text-white': !isScrolled, 'text-white': isScrolled }">
                                    <path stroke-linecap="round" stroke-linejoin="round" d="M16 7a4 4 0 11-8 0 4 4 0 018 0zM12 14a7 7 0 00-7 7h14a7 7 0 00-7-7z" />
                                </svg>

                                Login
                            </a>
                        @endauth
                    </div>

                    {{-- Tombol Hamburger Menu (Kanan) --}}
                    <div class="flex items-center">

                        {{-- Garis Pemisah (Berubah warna putih/abu-abu) --}}
                        <div class="hidden md:block h-6 border-l mx-3 transition-colors duration-300"
                            x-bind:class="{ 'border-gray-300': isScrolled, 'border-white': !isScrolled }"></div>

                        {{-- Ikon Hamburger Menu (Berubah warna hitam/putih) --}}
                        <button @click="open = !open" type="button"
                            class="inline-flex items-center justify-center p-2 rounded-md focus:outline-none transition-colors duration-300"
                            x-bind:class="{
                                // TEKS HITAM SAAT SCROLLED
                                'text-gray-700 hover:text-teal-600': isScrolled,
                                // TEKS PUTIH SAAT TRANSPARAN
                                'text-white hover:text-gray-300': !isScrolled
                            }"
                            aria-controls="mobile-menu" aria-expanded="false">
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

        {{-- DRAWER MENU (Z-50) (Tidak diubah, karena drawer selalu putih) --}}
        <div x-show="open"
            x-transition:enter="transition ease-in-out duration-300 transform"
            x-transition:enter-start="translate-x-full"
            x-transition:enter-end="translate-x-0"
            x-transition:leave="transition ease-in-out duration-300 transform"
            x-transition:leave-start="translate-x-0"
            x-transition:leave-end="translate-x-full"
            class="fixed inset-y-0 right-0 h-full bg-white shadow-xl z-50 overflow-y-auto w-80">

            {{-- KONTEN MOBILE (LENGKAP) --}}
            <div class="md:hidden">
                <div class="flex justify-between items-center px-4 py-4 mb-2">
                    <a href="{{ url('/') }}" class="flex items-center space-x-2">
                        <svg class="w-8 h-8 mr-1 text-teal-600" viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round" stroke-linejoin="round">
                            <path d="M3 9l9-7 9 7v11a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2z"></path>
                            <polyline points="9 22 9 12 15 12 15 22"></polyline>
                        </svg>
                        <span class="text-2xl font-bold text-teal-600">jogja jogja</span>
                    </a>
                    <button @click="open = false" class="p-2 text-gray-500 hover:text-gray-900 rounded-full hover:bg-gray-100">
                        <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                        </svg>
                    </button>
                </div>

                {{-- Search Bar --}}
                <div class="px-4 pb-4">
                    <div class="relative">
                        <input type="search" placeholder="Cari artikel ..." class="w-full py-2 pl-4 pr-10 text-base border border-gray-300 rounded-lg focus:ring-teal-600 focus:border-teal-600 bg-gray-100" />
                        <button type="submit" class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-500 hover:text-teal-600">
                            <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </button>
                    </div>
                </div>

                {{-- Menu Utama (Mobile) --}}
                <div class="divide-y divide-gray-100 border-t border-b border-gray-100">
                    <a href="{{ route('beranda') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">BERANDA</a>
                    <a href="{{ url('/penginapan') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">PENGINAPAN</a>
                    <a href="{{ url('/kuliner') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">KULINER</a>
                    <a href="{{ url('/wisata') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">WISATA</a>
                    <a href="{{ url('/event') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">EVENT</a>
                    <a href="{{ url('/lifestyle') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">LIFESTYLE</a>
                    <a href="{{ url('/artikel') }}" class="block px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">ARTIKEL</a>
                </div>

                {{-- Menu Informasi & Kemitraan (Mobile) (Konten lengkap dihilangkan untuk menjaga fokus) --}}
                {{-- ... Konten Mobile lainnya (Tidak diubah) ... --}}

                {{-- Menu Informasi & Kemitraan (Mobile) --}}
                <div class="divide-y divide-gray-100 border-b border-gray-100">
                    <a href="{{ url('/tentang-kami') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M13 16h-1v-4h-1m1-4h.01M21 12a9 9 0 11-18 0 9 9 0 0118 0z"></path>
                        </svg>
                        TENTANG KAMI
                    </a>
                    <a href="{{ url('/kemitraan') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.656-.126-1.283-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z"></path>
                        </svg>
                        KEMITRAAN
                    </a>
                    <a href="{{ url('/pasang-iklan') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 5.882V19.24a1.76 1.76 0 01-3.417.592l-2.147-6.15M18 13a3 3 0 100-6M5.436 13.683A4.001 4.001 0 017 6h1.832c4.1 0 7.625-1.234 9.168-3v14c-1.543-1.766-5.067-3-9.168-3H7a3.988 3.988 0 01-1.564-.317z"></path>
                        </svg>
                        PASANG IKLAN
                    </a>
                    <a href="{{ url('/syarat-ketentuan') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 12h6m-6 4h6m2 5H7a2 2 0 01-2-2V5a2 2 0 012-2h5.586a1 1 0 01.707.293l5.414 5.414a1 1 0 01.293.707V19a2 2 0 01-2 2z"></path>
                        </svg>
                        SYARAT & KETENTUAN
                    </a>
                    <a href="{{ url('/hubungi-kami') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                        </svg>
                        HUBUNGI KAMI
                    </a>
                    <a href="{{ route('login') ?? url('/login') }}" class="flex items-center px-4 py-3.5 text-lg font-semibold text-gray-800 hover:bg-gray-50 transition">
                        <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M11 16l-4-4m0 0l4-4m-4 4h14m-5 4v1a3 3 0 01-3 3H6a3 3 0 01-3-3V7a3 3 0 013-3h7a3 3 0 013 3v1"></path>
                        </svg>
                        LOGIN
                    </a>
                </div>

                {{-- Social Media Icons (Mobile) --}}
                <div class="flex justify-center space-x-4 mt-6 pt-4 border-t border-gray-100 px-4 pb-6">
                    <a href="https://instagram.com/jogjajogja.id" target="_blank" rel="noopener noreferrer" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:text-teal-600" aria-label="Instagram">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                        </svg>
                    </a>
                    <a href="https://tiktok.com/@jogjajogja.id" target="_blank" rel="noopener noreferrer" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:text-teal-600" aria-label="TikTok">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/>
                        </svg>
                    </a>
                    <a href="https://youtube.com/@jogjajogja" target="_blank" rel="noopener noreferrer" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:text-teal-600" aria-label="YouTube">
                        <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>
            
            {{-- KONTEN DESKTOP (HANYA KEMITRAAN/KONTAK) (Tidak diubah) --}}
            <div class="hidden md:block">
                <div class="px-4 py-4">
                    <div class="flex justify-between items-center pb-4 mb-4 border-b">
                        <a href="{{ url('/') }}" class="flex items-center space-x-2">
                            <span class="text-xl font-bold text-teal-600">jogjajogja.id</span>
                        </a>
                        <button @click="open = false" class="p-2 text-gray-500 hover:text-gray-900 rounded-full hover:bg-gray-100">
                            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M6 18L18 6M6 6l12 12"></path>
                            </svg>
                        </button>
                    </div>

                    {{-- Kemitraan --}}
                    <div class="mb-6">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Kemitraan</h3>
                        <div class="space-y-3">
                            <a href="{{ route('login') ?? url('/login/mitra') }}" class="flex items-center text-base text-gray-700 hover:text-teal-600 transition">
                                <svg class="w-5 h-5 mr-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 15v2m-6 4h12a2 2 0 002-2v-6a2 2 0 00-2-2H6a2 2 0 00-2 2v6a2 2 0 002 2zm10-10V7a4 4 0 00-8 0v4h8z"></path>
                                </svg>
                                Login Mitra
                            </a>
                            <a href="{{ route('register') ?? url('/register/mitra') }}" class="flex items-center text-base text-gray-700 hover:text-teal-600 transition">
                                <svg class="w-5 h-5 mr-3 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M18 9v3m0 0v3m0-3h3m-3 0h-3m-2-5a4 4 0 11-8 0 4 4 0 018 0zM3 20a6 6 0 0112 0v1H3v-1z"></path>
                                </svg>
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
                            <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            0895-3923-37544
                        </a>
                    </div>

                    {{-- Kontak Kami --}}
                    <div class="mb-6 border-t pt-4">
                        <h3 class="text-xl font-bold text-gray-900 mb-3">Kontak Kami</h3>
                        <div class="space-y-3">
                            <a href="mailto:info@jogjajogja.id" class="flex items-center text-base text-gray-700 hover:text-teal-600">
                                <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.848 5.232a2 2 0 002.304 0L21 8m-2-3H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2z"></path>
                                </svg>
                                info@jogjajogja.id
                            </a>
                            <a href="mailto:cs@jogjajogja.id" class="flex items-center text-base text-gray-700 hover:text-teal-600">
                                <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 8l7.848 5.232a2 2 0 002.304 0L21 8m-2-3H5a2 2 0 00-2 2v10a2 2 0 002 2h14a2 2 0 002-2V7a2 2 0 00-2-2z"></path>
                                </svg>
                                cs@jogjajogja.id
                            </a>
                            <a href="tel:0895392337544" class="flex items-center text-base text-gray-700 hover:text-teal-600">
                                <svg class="w-5 h-5 mr-3 text-teal-600 flex-shrink-0" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                                </svg>
                                0895-3923-37544
                            </a>
                        </div>
                    </div>

                    {{-- Social Media Icons (Desktop) --}}
                    <div class="flex space-x-4 mt-6 pt-4 border-t border-gray-100">
                        <a href="https://instagram.com/jogjajogja.id" target="_blank" rel="noopener noreferrer" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:text-teal-600" aria-label="Instagram">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M12 2.163c3.204 0 3.584.012 4.85.07 3.252.148 4.771 1.691 4.919 4.919.058 1.265.069 1.645.069 4.849 0 3.205-.012 3.584-.069 4.849-.149 3.225-1.664 4.771-4.919 4.919-1.266.058-1.644.07-4.85.07-3.204 0-3.584-.012-4.849-.07-3.26-.149-4.771-1.699-4.919-4.92-.058-1.265-.07-1.644-.07-4.849 0-3.204.013-3.583.07-4.849.149-3.227 1.664-4.771 4.919-4.919 1.266-.057 1.645-.069 4.849-.069zm0-2.163c-3.259 0-3.667.014-4.947.072-4.358.2-6.78 2.618-6.98 6.98-.059 1.281-.073 1.689-.073 4.948 0 3.259.014 3.668.072 4.948.2 4.358 2.618 6.78 6.98 6.98 1.281.058 1.689.072 4.948.072 3.259 0 3.668-.014 4.948-.072 4.354-.2 6.782-2.618 6.979-6.98.059-1.28.073-1.689.073-4.948 0-3.259-.014-3.667-.072-4.947-.196-4.354-2.617-6.78-6.979-6.98-1.281-.059-1.69-.073-4.949-.073zm0 5.838c-3.403 0-6.162 2.759-6.162 6.162s2.759 6.163 6.162 6.163 6.162-2.759 6.162-6.163c0-3.403-2.759-6.162-6.162-6.162zm0 10.162c-2.209 0-4-1.791-4-4s1.791-4 4-4 4 1.791 4 4-1.791 4-4 4zm6.406-11.845c-.796 0-1.441.645-1.441 1.44s.645 1.44 1.441 1.44c.795 0 1.439-.645 1.439-1.44s-.644-1.44-1.439-1.44z"/>
                            </svg>
                        </a>
                        <a href="https://tiktok.com/@jogjajogja.id" target="_blank" rel="noopener noreferrer" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:text-teal-600" aria-label="TikTok">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M19.59 6.69a4.83 4.83 0 01-3.77-4.25V2h-3.45v13.67a2.89 2.89 0 01-5.2 1.74 2.89 2.89 0 012.31-4.64 2.93 2.93 0 01.88.13V9.4a6.84 6.84 0 00-1-.05A6.33 6.33 0 005 20.1a6.34 6.34 0 0010.86-4.43v-7a8.16 8.16 0 004.77 1.52v-3.4a4.85 4.85 0 01-1-.1z"/>
                            </svg>
                        </a>
                        <a href="https://youtube.com/@jogjajogja" target="_blank" rel="noopener noreferrer" class="p-2 text-gray-700 transition border rounded-md hover:border-teal-600 hover:text-teal-600" aria-label="YouTube">
                            <svg class="w-7 h-7" fill="currentColor" viewBox="0 0 24 24">
                                <path d="M23.498 6.186a3.016 3.016 0 00-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 00.502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 002.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 002.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                            </svg>
                        </a>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    {{-- KONTEN UTAMA (MAINTAIN DENGAN HANYA flex-grow bg-gray-50) --}}
    <main class="flex-grow bg-gray-50">
        {{ $slot }}
    </main>

    {{-- Footer --}}
    <x-footer />

    {{-- Stack untuk JS kustom halaman --}}
    @stack('scripts')
</body>

</html>