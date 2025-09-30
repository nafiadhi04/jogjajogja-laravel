<x-app-layout>
    <div class="flex flex-col flex-1 overflow-y-auto bg-white">
        <main class="flex-1 p-4">

            {{-- Header Sambutan --}}
            <div class="mb-6">
                <h1 class="text-lg font-semibold text-gray-800">Selamat datang, {{ Auth::user()->name }}!</h1>
                <p class="mt-1 text-sm text-gray-600">Ringkasan aktivitas dashboard Anda.</p>
            </div>

            {{-- Tampilan untuk ADMIN --}}
            @if(Auth::user()->role === 'admin')
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-3">

                    {{-- Card Total Penginapan --}}
                    <div class="flex items-center justify-between p-4 bg-green-100 rounded-md shadow">
                        <div>
                            <h2 class="text-sm font-medium text-gray-800">Total Penginapan</h2>
                            <p class="mt-1 text-2xl font-bold text-green-700">{{ $totalPenginapan ?? 0 }}</p>
                        </div>
                        <div class="p-2 bg-green-200 rounded-full">
                            <span class="text-2xl text-green-700 material-symbols-outlined">villa</span>
                        </div>
                    </div>

                    {{-- Card Total Wisata --}}
                    <div class="flex items-center justify-between p-4 bg-blue-100 rounded-md shadow">
                        <div>
                            <h2 class="text-sm font-medium text-gray-800">Total Wisata</h2>
                            <p class="mt-1 text-2xl font-bold text-blue-700">{{ $totalWisata ?? 0 }}</p>
                        </div>
                        <div class="p-2 bg-blue-200 rounded-full">
                            <span class="text-2xl text-blue-700 material-symbols-outlined">landscape</span>
                        </div>
                    </div>

                    {{-- Card Total User --}}
                    <div class="flex items-center justify-between p-4 bg-purple-100 rounded-md shadow">
                        <div>
                            <h2 class="text-sm font-medium text-gray-800">Total User</h2>
                            <p class="mt-1 text-2xl font-bold text-purple-700">{{ $totalUsers ?? 0 }}</p>
                        </div>
                        <div class="p-2 bg-purple-200 rounded-full">
                            <span class="text-2xl text-purple-700 material-symbols-outlined">group</span>
                        </div>
                    </div>
                </div>

                {{-- Tampilan untuk MEMBER --}}
            @else
                {{-- Statistik Penginapan --}}
                <h3 class="mb-3 text-sm font-semibold text-gray-700">Statistik Penginapan Saya</h3>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <div class="p-4 bg-green-100 rounded-md shadow">
                        <h2 class="text-sm font-medium text-gray-800">Total Artikel</h2>
                        <p class="mt-1 text-2xl font-bold text-green-700">{{ $totalPenginapan ?? 0 }}</p>
                    </div>
                    <div class="p-4 bg-yellow-100 rounded-md shadow">
                        <h2 class="text-sm font-medium text-gray-800">Verifikasi</h2>
                        <p class="mt-1 text-2xl font-bold text-yellow-700">{{ $penginapanVerifikasi ?? 0 }}</p>
                    </div>
                    <div class="p-4 bg-red-100 rounded-md shadow">
                        <h2 class="text-sm font-medium text-gray-800">Revisi</h2>
                        <p class="mt-1 text-2xl font-bold text-red-700">{{ $penginapanRevisi ?? 0 }}</p>
                    </div>
                    <div class="p-4 bg-blue-100 rounded-md shadow">
                        <h2 class="text-sm font-medium text-gray-800">Diterima</h2>
                        <p class="mt-1 text-2xl font-bold text-blue-700">{{ $penginapanDiterima ?? 0 }}</p>
                    </div>
                </div>

                {{-- Statistik Wisata --}}
                <h3 class="mt-6 mb-3 text-sm font-semibold text-gray-700">Statistik Wisata Saya</h3>
                <div class="grid grid-cols-1 gap-4 md:grid-cols-2 lg:grid-cols-4">
                    <div class="p-4 bg-green-100 rounded-md shadow">
                        <h2 class="text-sm font-medium text-gray-800">Total Artikel</h2>
                        <p class="mt-1 text-2xl font-bold text-green-700">{{ $totalWisata ?? 0 }}</p>
                    </div>
                    <div class="p-4 bg-yellow-100 rounded-md shadow">
                        <h2 class="text-sm font-medium text-gray-800">Verifikasi</h2>
                        <p class="mt-1 text-2xl font-bold text-yellow-700">{{ $wisataVerifikasi ?? 0 }}</p>
                    </div>
                    <div class="p-4 bg-red-100 rounded-md shadow">
                        <h2 class="text-sm font-medium text-gray-800">Revisi</h2>
                        <p class="mt-1 text-2xl font-bold text-red-700">{{ $wisataRevisi ?? 0 }}</p>
                    </div>
                    <div class="p-4 bg-blue-100 rounded-md shadow">
                        <h2 class="text-sm font-medium text-gray-800">Diterima</h2>
                        <p class="mt-1 text-2xl font-bold text-blue-700">{{ $wisataDiterima ?? 0 }}</p>
                    </div>
                </div>
            @endif

        </main>
    </div>
</x-app-layout>