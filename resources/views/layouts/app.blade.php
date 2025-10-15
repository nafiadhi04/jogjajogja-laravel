<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">

<head>
    <meta charset="utf-8" />
    <meta name="viewport" content="width=device-width, initial-scale=1" />
    <meta name="csrf-token" content="{{ csrf_token() }}" />

    <title>{{ config('app.name', 'Laravel') }}</title>

    <link rel="preconnect" href="https://fonts.bunny.net" />
    <link href="https://fonts.bunny.net/css?family=figtree:400,500,600&display=swap" rel="stylesheet" />
    <link rel="stylesheet"
        href="https://fonts.googleapis.com/css2?family=Material+Symbols+Outlined:opsz,wght,FILL,GRAD@24,400,0,0" />

    @vite(['resources/css/app.css', 'resources/js/app.js'])

    @stack('styles')
</head>

<body class="font-sans antialiased">
    @php
$user = auth()->user();
    @endphp

    {{-- Root Alpine: menyimpan state "open" untuk sidebar --}}
    <div x-data="{ open: window.innerWidth > 1024 }" class="flex min-h-screen bg-gray-100">

        {{-- 1. Komponen Sidebar (compact) --}}
        <x-sidebar :user="$user" />

        {{-- 2. Area Konten Utama --}}
        <div class="flex flex-col flex-1 min-w-0 overflow-hidden">
            <div class="flex flex-col flex-1">
                <main class="flex-1 overflow-y-auto">

                    {{-- Header: disesuaikan tinggi & padding untuk proporsi compact --}}
                    <div class="flex items-center justify-between h-12 p-1 border-b border-slate-700 bg-slate-800">
                        {{-- Tombol Toggle (stop propagation agar @click.away tidak langsung menutup) --}}
                        <button @click.stop="open = !open"
                            class="flex items-center justify-center text-gray-400 transition rounded-md w-9 h-9 hover:bg-slate-700">
                            <span class="text-lg material-symbols-outlined">menu</span>
                        </button>

                        {{-- Dropdown User --}}
                        <div class="hidden sm:flex sm:items-center sm:ms-6">
                            <x-dropdown align="right" width="48">
                                <<x-slot name="trigger">
    <button
        class="inline-flex items-center gap-2 px-2 text-sm font-medium leading-none text-white transition duration-150 ease-in-out border border-transparent rounded-md h-9 bg-slate-800 hover:text-gray-200 focus:outline-none"
        type="button">
        {{-- avatar lebih kecil agar tidak mendorong tinggi header --}}
        <img
            class="object-cover w-6 h-6 rounded-full"
            src="{{ Auth::user()->profile_photo_path
    ? asset('storage/' . Auth::user()->profile_photo_path)
    : 'https://ui-avatars.com/api/?name=' . urlencode(Auth::user()->name) . '&color=7F9CF5&background=EBF4FF' }}"
            alt="{{ Auth::user()->name }}">

        {{-- nama dipotong dan tidak membungkus; tampilkan hanya di sm+ --}}
        <div class="hidden sm:block max-w-[9rem] text-xs truncate">
            {{ Auth::user()->name }}
        </div>

        {{-- ikon kecil --}}
        <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 20 20" fill="currentColor">
            <path fill-rule="evenodd"
                d="M5.293 7.293a1 1 0 011.414 0L10 10.586l3.293-3.293a1 1 0 111.414 1.414l-4 4a1 1 0 01-1.414 0l-4-4a1 1 0 010-1.414z"
                clip-rule="evenodd" />
        </svg>
    </button>
</x-slot>

                                <x-slot name="content">
                                    <x-dropdown-link :href="route('profile.edit')">
                                        {{ __('Profile') }}
                                    </x-dropdown-link>

                                    <form method="POST" action="{{ route('logout') }}">
                                        @csrf
                                        <x-dropdown-link :href="route('logout')"
                                            onclick="event.preventDefault(); this.closest('form').submit();">
                                            {{ __('Log Out') }}
                                        </x-dropdown-link>
                                    </form>
                                </x-slot>
                            </x-dropdown>
                        </div>
                    </div>

                    {{-- Slot untuk konten utama dari setiap halaman --}}
                    <div class="p-4">
                        {{ $slot }}
                    </div>
                </main>
            </div>
        </div>
    </div>

    @stack('scripts')
</body>

</html>