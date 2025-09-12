<x-app-layout>
    @php
        // Mengambil data user yang sedang login
        $user = auth()->user();
    @endphp

    <div class="flex h-screen bg-gray-100">


        {{-- Area Konten Utama --}}
        <div class="flex flex-col flex-1 overflow-y-auto">

            {{-- Konten Halaman --}}
            <main class="flex-1 p-6">

                {{-- Header Sambutan --}}
                <div class="mb-8">
                    <h1 class="text-2xl font-bold text-gray-800">Selamat datang, {{ $user->name }}!</h1>
                    <p class="mt-1 text-gray-600">Ini adalah ringkasan aktivitas di dashboard Anda.</p>
                </div>

                {{-- Grid untuk Kartu Statistik --}}
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                    <div class="p-6 bg-indigo-100 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-800">Jumlah Wisata</h2>
                        <p class="mt-2 text-3xl font-bold text-indigo-700">0</p>
                    </div>

                    <div class="p-6 bg-green-100 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-800">Jumlah Penginapan</h2>
                        <p class="mt-2 text-3xl font-bold text-green-700">0</p>
                    </div>

                    <div class="p-6 bg-yellow-100 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-800">Jumlah User</h2>
                        {{-- Menggunakan null coalescing operator untuk nilai default --}}
                        <p class="mt-2 text-3xl font-bold text-yellow-700">{{ $totalUsers ?? 0 }}</p>
                    </div>

                </div>

                {{-- Konten Lainnya Bisa Ditambahkan di Sini --}}
                <div class="mt-8">
                    {{-- Contoh: Tabel data atau chart --}}
                </div>

            </main>
        </div>
    </div>
</x-app-layout>