<x-app-layout>
    {{-- Latar belakang putih untuk seluruh area konten --}}
    <div class="flex flex-col flex-1 overflow-y-auto bg-white">
        <main class="flex-1 p-6">
            {{-- Header Sambutan --}}
            <div class="mb-8">
                <h1 class="text-2xl font-bold text-gray-800">Selamat datang, {{ Auth::user()->name }}!</h1>
                <p class="mt-1 text-gray-600">Ini adalah ringkasan aktivitas di dashboard Anda.</p>
            </div>

            {{-- ========================================================== --}}
            {{-- Tampilan untuk ADMIN --}}
            {{-- ========================================================== --}}
            @if(Auth::user()->role === 'admin')
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3">

                    {{-- Card Total Penginapan --}}
                    <div class="flex items-center justify-between p-6 bg-green-100 rounded-lg shadow-md">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Total Penginapan</h2>
                            <p class="mt-2 text-3xl font-bold text-green-700">{{ $totalPenginapan ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-green-200 rounded-full">
                            {{-- PERBAIKAN: Ikon diganti dengan Google Material Symbols --}}
                            <span class="text-4xl text-green-700 material-symbols-outlined">
                                villa
                            </span>
                        </div>
                    </div>

                    {{-- Card Total Wisata (Placeholder) --}}
                    <div class="flex items-center justify-between p-6 bg-blue-100 rounded-lg shadow-md">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Total Wisata</h2>
                            <p class="mt-2 text-3xl font-bold text-blue-700">0</p>
                        </div>
                        <div class="p-3 bg-blue-200 rounded-full">
                            {{-- PERBAIKAN: Ikon diganti dengan Google Material Symbols --}}
                            <span class="text-4xl text-blue-700 material-symbols-outlined">
                                landscape
                            </span>
                        </div>
                    </div>

                    {{-- Card Total User --}}
                    <div class="flex items-center justify-between p-6 bg-purple-100 rounded-lg shadow-md">
                        <div>
                            <h2 class="text-lg font-semibold text-gray-800">Total User</h2>
                            <p class="mt-2 text-3xl font-bold text-purple-700">{{ $totalUsers ?? 0 }}</p>
                        </div>
                        <div class="p-3 bg-purple-200 rounded-full">
                            {{-- PERBAIKAN: Ikon diganti dengan Google Material Symbols --}}
                            <span class="text-4xl text-purple-700 material-symbols-outlined">
                                group
                            </span>
                        </div>
                    </div>

                </div>

                {{-- ========================================================== --}}
                {{-- Tampilan untuk MEMBER --}}
                {{-- ========================================================== --}}
            @else
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                    {{-- Card Total Artikel Saya --}}
                    <div class="p-6 bg-green-100 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-800">Total Artikel Saya</h2>
                        <p class="mt-2 text-3xl font-bold text-green-700">{{ $totalPenginapan ?? 0 }}</p>
                    </div>
                    {{-- Card Menunggu Verifikasi --}}
                    <div class="p-6 bg-yellow-100 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-800">Menunggu Verifikasi</h2>
                        <p class="mt-2 text-3xl font-bold text-yellow-700">{{ $penginapanVerifikasi ?? 0 }}</p>
                    </div>
                    {{-- Card Perlu Direvisi --}}
                    <div class="p-6 bg-red-100 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-800">Perlu Direvisi</h2>
                        <p class="mt-2 text-3xl font-bold text-red-700">{{ $penginapanRevisi ?? 0 }}</p>
                    </div>
                    {{-- Card Sudah Diterima --}}
                    <div class="p-6 bg-blue-100 rounded-lg shadow-md">
                        <h2 class="text-lg font-semibold text-gray-800">Sudah Diterima</h2>
                        <p class="mt-2 text-3xl font-bold text-blue-700">{{ $penginapanDiterima ?? 0 }}</p>
                    </div>
                </div>
            @endif

        </main>
    </div>
</x-app-layout>
