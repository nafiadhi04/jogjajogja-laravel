<x-guest-layout>
    {{-- Hero Section dengan Background Image --}}
    <div class="relative bg-center bg-no-repeat bg-cover"
        style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1507613621237-7a47e7a2365e?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2070&q=80');">

        <div class="absolute inset-0 bg-gradient-to-r from-cyan-500/20 to-green-500/20"></div>

        <div class="relative flex flex-col items-center justify-center min-h-[60vh] px-4">
            <h1 class="mb-8 text-4xl font-bold text-center text-white md:text-6xl">
                Jelajahi Pesona Wisata
            </h1>
            <div class="mb-12">
                <div class="px-6 py-3 text-white rounded-lg bg-cyan-600">
                    <span class="text-lg font-medium">Beranda > Wisata</span>
                </div>
            </div>
        </div>
    </div>

    {{-- Search & Filter Section --}}
    <div class="relative z-10 max-w-5xl py-12 mx-4 -mt-24 bg-white shadow-2xl md:mx-auto rounded-2xl">
        <div class="px-8">
            <form method="GET" action="{{ route('wisata.list') }}" class="grid grid-cols-1 gap-6 md:grid-cols-4">
                {{-- Tipe Wisata --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Tipe Wisata:</label>
                    <select name="tipe"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg appearance-none bg-gray-50 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="">Semua Tipe</option>
                        {{-- Data ini perlu dikirim dari PageController --}}
                        {{-- @foreach($all_tipes as $tipe) <option value="{{ $tipe }}"
                            @selected(request('tipe')==$tipe)>{{ $tipe }}</option> @endforeach --}}
                    </select>
                </div>

                {{-- Kota --}}
                <div>
                    <label class="block mb-2 text-sm font-medium text-gray-700">Kota:</label>
                    <select name="kota"
                        class="w-full px-4 py-3 border border-gray-300 rounded-lg appearance-none bg-gray-50 focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                        <option value="">Semua Kota</option>
                        {{-- Data ini perlu dikirim dari PageController --}}
                        {{-- @foreach($all_kotas as $kota) <option value="{{ $kota }}"
                            @selected(request('kota')==$kota)>{{ $kota }}</option> @endforeach --}}
                    </select>
                </div>

                {{-- Rentang Harga Tiket --}}
                <div class="md:col-span-2">
                    <label class="block mb-2 text-sm font-medium text-gray-700">Harga Tiket:</label>
                    <div class="grid grid-cols-2 gap-2">
                        <input type="number" name="harga_min" value="{{ request('harga_min') }}"
                            placeholder="Harga minimum"
                            class="px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                        <input type="number" name="harga_max" value="{{ request('harga_max') }}"
                            placeholder="Harga maksimum"
                            class="px-3 py-3 border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                    </div>
                </div>

                {{-- Tombol Aksi --}}
                <div class="flex justify-center gap-4 mt-4 md:col-span-4">
                    <button type="submit"
                        class="flex items-center px-8 py-3 font-medium text-white transition-colors duration-200 bg-orange-500 rounded-lg hover:bg-orange-600">
                        <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                        </svg>
                        Pencarian
                    </button>
                    <a href="{{ route('wisata.list') }}"
                        class="px-6 py-3 font-medium text-white transition-colors duration-200 bg-gray-500 rounded-lg hover:bg-gray-600">
                        Reset Filter
                    </a>
                </div>
            </form>
        </div>
    </div>

    {{-- Results Section --}}
    <div class="py-12 bg-gray-50">
        <div class="px-6 mx-auto max-w-7xl">

            <div class="flex flex-col mb-8 md:flex-row md:items-center md:justify-between">
                <div>
                    <h2 class="text-2xl font-bold text-gray-800">
                        @isset($wisatas) {{ $wisatas->total() }} Wisata Ditemukan @else 0 Wisata Ditemukan @endisset
                    </h2>
                </div>
                <div class="mt-4 md:mt-0">
                    <form method="GET" action="{{ route('wisata.list') }}" class="flex items-center space-x-4">
                        @foreach(request()->except(['sort_by']) as $key => $value)
                            <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                        @endforeach
                        <span class="text-gray-600">Urutkan:</span>
                        <select name="sort_by" onchange="this.form.submit()"
                            class="px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-cyan-500 focus:border-cyan-500">
                            <option value="created_at" @selected(request('sort_by', 'created_at') == 'created_at')>Terbaru
                            </option>
                            <option value="harga_tiket" @selected(request('sort_by') == 'harga_tiket')>Termurah</option>
                            <option value="nama" @selected(request('sort_by') == 'nama')>Nama A-Z</option>
                        </select>
                    </form>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @isset($wisatas)
                    @forelse ($wisatas as $item)
                        <div
                            class="overflow-hidden transition-shadow duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl group">
                            <div class="relative overflow-hidden">
                                <a href="{{ route('wisata.detail', $item->slug) }}">
                                    @if($item->thumbnail)
                                        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->nama }}"
                                            class="object-cover w-full h-48 transition-transform duration-300 group-hover:scale-105">
                                    @else
                                        <div
                                            class="flex items-center justify-center w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                                            <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor"
                                                viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                                </path>
                                            </svg>
                                        </div>
                                    @endif
                                </a>
                            </div>
                            <div class="p-6">
                                <h3 class="mb-3 text-xl font-bold text-gray-800 transition-colors group-hover:text-cyan-600">
                                    <a href="{{ route('wisata.detail', $item->slug) }}">{{ $item->nama }}</a>
                                </h3>
                                <div class="flex items-center mb-2 text-gray-600">
                                    <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                        <path fill-rule="evenodd"
                                            d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z"
                                            clip-rule="evenodd"></path>
                                    </svg>
                                    <span class="text-sm">{{ $item->kota }}</span>
                                </div>
                                <div class="flex items-center mb-4 text-gray-600">
                                    <span class="mr-2 text-base material-symbols-outlined">category</span>
                                    <span class="text-sm">{{ $item->tipe }}</span>
                                </div>
                                <div class="pt-4 border-t">
                                    <span class="text-xl font-bold text-cyan-600">
                                        @if($item->harga_tiket > 0)
                                            Rp{{ number_format($item->harga_tiket, 0, ',', '.') }}
                                        @else
                                            Gratis
                                        @endif
                                    </span>
                                </div>
                            </div>
                        </div>
                    @empty
                        <div class="py-12 text-center col-span-full">
                            <h3 class="mb-2 text-xl font-semibold text-gray-700">Tidak ada tempat wisata ditemukan</h3>
                            <p class="mb-4 text-gray-500">Coba ubah filter pencarian Anda.</p>
                        </div>
                    @endforelse
                @endisset
            </div>

            @isset($wisatas)
                @if($wisatas->hasPages())
                    <div class="flex justify-center mt-12">
                        {{ $wisatas->appends(request()->query())->links() }}
                    </div>
                @endif
            @endisset
        </div>
    </div>
</x-guest-layout>