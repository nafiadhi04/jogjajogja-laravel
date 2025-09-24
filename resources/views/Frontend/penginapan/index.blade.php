<x-guest-layout>
    {{-- Hero Section dengan Background Image --}}
    <div class="relative min-h-screen bg-center bg-no-repeat bg-cover" 
        style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://cdn-image.hipwee.com/wp-content/uploads/2020/07/hipwee-jenispenginapan5-768x512.jpg');">
        
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-blue-500/20"></div>
        
        <div class="relative flex flex-col items-center justify-center min-h-screen px-4">
            {{-- Main Title --}}
            <h1 class="mb-8 text-4xl font-bold text-center text-white md:text-6xl">
                Rekomendasi Penginapan
            </h1>
            
            {{-- Breadcrumb --}}
            <div class="mb-12">
                <div class="px-6 py-3 text-white bg-teal-600 rounded-lg">
                    <span class="text-lg font-medium">Beranda > Penginapan</span>
                </div>
            </div>
            
            {{-- Search Form - CORRECTED --}}
            <div class="w-full max-w-5xl p-8 bg-white shadow-2xl rounded-2xl">
                <form method="GET" action="{{ route('penginapan.list') }}" class="grid grid-cols-1 gap-6 md:grid-cols-4">
                    {{-- Tipe Penginapan --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Tipe Penginapan:</label>
                        <select name="tipe" class="w-full px-4 py-3 border border-gray-300 rounded-lg appearance-none bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Semua Tipe</option>
                            @foreach($all_tipes as $tipe)
                                <option value="{{ $tipe }}" {{ request('tipe') == $tipe ? 'selected' : '' }}>
                                    {{ $tipe }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Kota --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Kota:</label>
                        <select name="kota" class="w-full px-4 py-3 border border-gray-300 rounded-lg appearance-none bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Semua Kota</option>
                            @foreach($all_kotas as $kota)
                                <option value="{{ $kota }}" {{ request('kota') == $kota ? 'selected' : '' }}>
                                    {{ $kota }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Periode Harga - CORRECTED --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Periode:</label>
                        <select name="periode" class="w-full px-4 py-3 border border-gray-300 rounded-lg appearance-none bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="">Semua Periode</option>
                            @foreach($periode_options as $periode)
                                <option value="{{ $periode }}" {{ request('periode') == $periode ? 'selected' : '' }}>
                                    {{ $periode }}
                                </option>
                            @endforeach
                        </select>
                    </div>
                    
                    {{-- Pencarian --}}
                    <div>
                        <label class="block mb-2 text-sm font-medium text-gray-700">Pencarian:</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penginapan..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    
                    {{-- Button Pencarian --}}
                    <div class="flex justify-center gap-4 mt-4 md:col-span-4">
                        <button type="submit" 
                                class="flex items-center px-8 py-3 font-medium text-white transition-colors duration-200 bg-orange-500 rounded-lg hover:bg-orange-600">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Pencarian
                        </button>
                        <a href="{{ route('penginapan.list') }}" 
                           class="px-6 py-3 font-medium text-white transition-colors duration-200 bg-gray-500 rounded-lg hover:bg-gray-600">
                            Reset Filter
                        </a>
                    </div>
                </form>

                {{-- Advanced Filter Toggle --}}
                <div class="pt-6 mt-6 border-t">
                    <button type="button" id="toggleAdvanced" 
                            class="flex items-center font-medium text-teal-600 hover:text-teal-700">
                        <span>Filter Lanjutan</span>
                        <svg class="w-4 h-4 ml-2 transition-transform transform" id="advancedArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    {{-- Advanced Filters --}}
                    <div id="advancedFilters" class="hidden p-4 mt-4 rounded-lg bg-gray-50">
                        <form method="GET" action="{{ route('penginapan.list') }}" class="grid grid-cols-1 gap-4 md:grid-cols-3">
                            
                            {{-- Preserve other filters --}}
                            @foreach(request()->except(['harga_min', 'harga_max']) as $key => $value)
                                @if(is_array($value))
                                    @foreach($value as $v)
                                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                    @endforeach
                                @else
                                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                                @endif
                            @endforeach
                            
                            {{-- Price Range Dropdown - **PERBAIKI INI** --}}
                            <div class="md:col-span-2">
                                <label class="block mb-2 text-sm font-medium text-gray-700">Rentang Harga:</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <select name="harga_min" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none">
                                        <option value="">Harga Minimum</option>
                                        <option value="0" {{ request('harga_min') == '0' ? 'selected' : '' }}>0</option>
                                        <option value="500000" {{ request('harga_min') == '500000' ? 'selected' : '' }}>Rp 500.000</option>
                                        <option value="1000000" {{ request('harga_min') == '1000000' ? 'selected' : '' }}>Rp 1.000.000</option>
                                        <option value="1500000" {{ request('harga_min') == '1500000' ? 'selected' : '' }}>Rp 1.500.000</option>
                                        <option value="2000000" {{ request('harga_min') == '2000000' ? 'selected' : '' }}>Rp 2.000.000</option>
                                        <option value="3000000" {{ request('harga_min') == '3000000' ? 'selected' : '' }}>Rp 3.000.000</option>
                                        <option value="5000000" {{ request('harga_min') == '5000000' ? 'selected' : '' }}>Rp 5.000.000</option>
                                    </select>
                                    <select name="harga_max" class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none">
                                        <option value="">Harga Maksimum</option>
                                        <option value="500000" {{ request('harga_max') == '500000' ? 'selected' : '' }}>Rp 500.000</option>
                                        <option value="1000000" {{ request('harga_max') == '1000000' ? 'selected' : '' }}>Rp 1.000.000</option>
                                        <option value="1500000" {{ request('harga_max') == '1500000' ? 'selected' : '' }}>Rp 1.500.000</option>
                                        <option value="2000000" {{ request('harga_max') == '2000000' ? 'selected' : '' }}>Rp 2.000.000</option>
                                        <option value="3000000" {{ request('harga_max') == '3000000' ? 'selected' : '' }}>Rp 3.000.000</option>
                                        <option value="5000000" {{ request('harga_max') == '5000000' ? 'selected' : '' }}>Rp 5.000.000</option>
                                        <option value="10000000" {{ request('harga_max') == '10000000' ? 'selected' : '' }}>Rp 10.000.000</option>
                                    </select>
                                </div>
                            </div>
                            {{-- Submit Advanced --}}
                            <div class="flex items-end">
                                <button type="submit" 
                                        class="w-full px-4 py-2 text-white transition-colors duration-200 bg-teal-600 rounded-lg hover:bg-teal-700">
                                    Terapkan Filter
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>
        </div>
    </div>

    {{-- Results Section --}}
    <div class="py-12 bg-gray-50">
        <div class="px-6 mx-auto max-w-7xl">
            
            {{-- Active Filters Display --}}
            @if(request()->hasAny(['tipe', 'kota', 'harga_min', 'harga_max', 'periode', 'fasilitas', 'search']))
                <div class="p-4 mb-6 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-700">Filter Aktif:</h3>
                        <a href="{{ route('penginapan.list') }}" class="text-sm text-red-600 hover:text-red-700">Hapus Semua Filter</a>
                    </div>
                    <div class="flex flex-wrap gap-2 mt-2">
                        @if(request('tipe'))
                            <span class="px-3 py-1 text-sm text-blue-800 bg-blue-100 rounded-full">
                                Tipe: {{ request('tipe') }}
                            </span>
                        @endif
                        @if(request('kota'))
                            <span class="px-3 py-1 text-sm text-green-800 bg-green-100 rounded-full">
                                Kota: {{ request('kota') }}
                            </span>
                        @endif
                        @if(request('periode'))
                            <span class="px-3 py-1 text-sm text-purple-800 bg-purple-100 rounded-full">
                                Periode: {{ request('periode') }}
                            </span>
                        @endif
                        @if(request('search'))
                            <span class="px-3 py-1 text-sm text-yellow-800 bg-yellow-100 rounded-full">
                                Pencarian: "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('harga_min') || request('harga_max'))
                            <span class="px-3 py-1 text-sm text-orange-800 bg-orange-100 rounded-full">
                                Harga: Rp {{ number_format(request('harga_min', 0)) }} - Rp {{ number_format(request('harga_max', 999999999)) }}
                            </span>
                        @endif
                        @if(request('fasilitas'))
                            @foreach((array) request('fasilitas') as $fasilitasId)
                                @php
                                    $fasilitas = $all_fasilitas->find($fasilitasId);
                                @endphp
                                @if($fasilitas)
                                    <span class="px-3 py-1 text-sm text-indigo-800 bg-indigo-100 rounded-full">
                                        {{ $fasilitas->nama }}
                                    </span>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
            
            {{-- Results Header --}}
<div class="flex flex-col mb-8 md:flex-row md:items-center md:justify-between">
    <div>
        <h2 class="text-2xl font-bold text-gray-800">
            {{ $penginapan->total() }} Penginapan Ditemukan
            @if(request('kota'))
                di {{ request('kota') }}
            @endif
        </h2>
    </div>
    <div class="mt-4 md:mt-0">
        <form method="GET" action="{{ route('penginapan.list') }}" class="flex items-center space-x-4">
            {{-- Preserve current filters --}}
            @foreach(request()->except(['sort_by']) as $key => $value)
                @if(is_array($value))
                    @foreach($value as $v)
                        <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                    @endforeach
                @else
                    <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                @endif
            @endforeach
            
            {{-- ✨ Bagian yang Diperbaiki: Ikon di kiri dan kanan teks ✨ --}}
            <div class="flex items-center space-x-2 text-gray-600">
                <span>Sort by:</span>

                {{-- Panah Bawah di Kanan --}}
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M19 14l-7 7m0 0l-7-7m7 7V3" />
                </svg>
                     {{-- Panah Atas di Kiri --}}
                <svg class="w-4 h-4" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24" stroke="currentColor" stroke-width="2">
                    <path stroke-linecap="round" stroke-linejoin="round" d="M5 10l7-7m0 0l7 7m-7-7v18" />
                </svg>
            </div>
            
            <select name="sort_by" onchange="this.form.submit()" 
                    class="px-4 py-2 bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none pr-8">
                <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                <option value="harga" {{ request('sort_by') == 'harga' ? 'selected' : '' }}>Termurah</option>
                <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Abjad (A-Z)</option>
                <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Rekomendasi</option>
            </select>
        </form>
    </div>
</div>
            {{-- Grid Penginapan --}}
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4">
                @forelse ($penginapan as $index => $item)
                    <div class="overflow-hidden transition-shadow duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl group">

                        <div class="relative overflow-hidden">
                            <a href="{{ route('penginapan.detail', $item->slug) }}">
                                @if($item->thumbnail)
                                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->nama }}"
                                        class="object-cover w-full h-48 transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex items-center justify-center w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            
                            {{-- Rekomendasi Badge - based on index (temporary) --}}
                            @if($index < 8)
                                <div class="absolute top-4 left-4">
                                    <div class="flex items-center px-3 py-1 text-sm font-medium text-white bg-orange-500 rounded-lg">
                                        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        Rekomendasi
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-6">
                            {{-- Title --}}
                            <h3 class="mb-3 text-xl font-bold text-gray-800 transition-colors group-hover:text-teal-600">
                                <a href="{{ route('penginapan.detail', $item) }}">{{ $item->nama }}</a>
                            </h3>
                            {{-- Location --}}
                            <div class="flex items-center mb-2 text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm">{{ $item->kota }}</span>
                            </div>

                            {{-- Type --}}
                            <div class="flex items-center mb-2 text-gray-600">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                <span class="text-sm">{{ $item->tipe }}</span>
                            </div>

                            {{-- Price & Views Container --}}
                            <div class="flex items-center justify-between">
                                {{-- Price dengan potongan miring --}}
                                <div class="px-4 py-2 text-white bg-teal-600 clip-slant">
                                    <div class="flex flex-col leading-tight">
                                        <span class="text-sm">Harian</span>
                                        <span class="text-xl font-bold">
                                            Rp{{ number_format($item->harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                {{-- Views (kanan) --}}
                                <div class="flex items-center pr-3 space-x-1 text-sm font-medium text-gray-700">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>{{ $item->views }}</span>
                                </div>
                            </div>
                            
                            {{-- Style custom untuk efek potongan miring --}}
                            <style>
                                .clip-slant {
                                    clip-path: polygon(0 0, 90% 0, 100% 100%, 0% 100%);
                                    border-bottom-left-radius: 0.5rem; /* biar ada rounded kiri bawah */
                                }
                            </style>

                            {{-- Rating - ADDED --}}
                            @if($item->rating > 0)
                                <div class="flex items-center mb-2 text-gray-600">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-4 h-4 {{ $i <= $item->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-sm">({{ $item->rating }})</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
<<<<<<< HEAD
=======

                    {{-- Style custom untuk efek potongan miring --}}
                    <style>
                        .clip-slant {
                            clip-path: polygon(0 0, 90% 0, 100% 100%, 0% 100%);
                            border-bottom-left-radius: 0.5rem; /* biar ada rounded kiri bawah */
                        }
                    </style>

                                                {{-- Rating - ADDED --}}
                                                @if($item->rating > 0)
                                                    <div class="flex items-center mb-2 text-gray-600">
                                                        <div class="flex items-center">
                                                            @for($i = 1; $i <= 5; $i++)
                                                                <svg class="w-4 h-4 {{ $i <= $item->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                                    <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                                                </svg>
                                                            @endfor
                                                            <span class="ml-2 text-sm">({{ $item->rating }})</span>
                                                        </div>
                                                    </div>
                                                @endif
                                            </div>
                                        </div>
>>>>>>> 4662a86f3c1b99c37d9062ae1c5ca682fb6fde1f
                @empty
                    <div class="py-12 text-center col-span-full">
                        <div class="mb-6">
                            <svg class="w-16 h-16 mx-auto text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-xl font-semibold text-gray-700">Tidak ada penginapan ditemukan</h3>
                        <p class="mb-4 text-gray-500">Coba ubah filter pencarian atau hapus beberapa kriteria</p>
                        <a href="{{ route('penginapan.list') }}" 
                           class="px-6 py-2 text-white transition-colors bg-teal-600 rounded-lg hover:bg-teal-700">
                            Lihat Semua Penginapan
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($penginapan->hasPages())
                <div class="flex justify-center mt-12">
                    {{ $penginapan->appends(request()->query())->links() }}
                </div>
            @endif
        </div>
    </div>

    {{-- Custom CSS --}}
    <style>
        /* Custom Select Arrow */
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }
        
        /* Hover effect for cards */
        .group:hover .group-hover\:scale-105 {
            transform: scale(1.05);
        }
        
        /* Smooth transitions */
        * {
            transition-property: color, background-color, border-color, text-decoration-color, fill, stroke, opacity, box-shadow, transform, filter, backdrop-filter;
            transition-timing-function: cubic-bezier(0.4, 0, 0.2, 1);
            transition-duration: 150ms;
        }
    </style>

    {{-- JavaScript untuk Advanced Filter Toggle dan Auto Submit --}}
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Advanced Filter Toggle
            const toggleBtn = document.getElementById('toggleAdvanced');
            const advancedFilters = document.getElementById('advancedFilters');
            const arrow = document.getElementById('advancedArrow');
            
            if (toggleBtn && advancedFilters && arrow) {
                toggleBtn.addEventListener('click', function() {
                    advancedFilters.classList.toggle('hidden');
                    arrow.classList.toggle('rotate-180');
                });
            }
        });
    </script>
</x-guest-layout>
