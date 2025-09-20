<x-guest-layout>
    {{-- Hero Section dengan Background Image --}}
    <div class="relative min-h-screen bg-cover bg-center bg-no-repeat" 
         style="background-image: linear-gradient(rgba(0,0,0,0.4), rgba(0,0,0,0.4)), url('https://images.unsplash.com/photo-1559827260-dc66d52bef19?ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxwaG90by1wYWdlfHx8fGVufDB8fHx8fA%3D%3D&auto=format&fit=crop&w=2074&q=80');">
        
        <div class="absolute inset-0 bg-gradient-to-r from-teal-500/20 to-blue-500/20"></div>
        
        <div class="relative flex flex-col items-center justify-center min-h-screen px-4">
            {{-- Main Title --}}
            <h1 class="text-4xl md:text-6xl font-bold text-white text-center mb-8">
                Rekomendasi Penginapan
            </h1>
            
            {{-- Breadcrumb --}}
            <div class="mb-12">
                <div class="bg-teal-600 text-white px-6 py-3 rounded-lg">
                    <span class="text-lg font-medium">Beranda > Penginapan</span>
                </div>
            </div>
            
            {{-- Search Form - CORRECTED --}}
            <div class="bg-white rounded-2xl shadow-2xl p-8 w-full max-w-5xl">
                <form method="GET" action="{{ route('penginapan.list') }}" class="grid grid-cols-1 md:grid-cols-4 gap-6">
                    {{-- Tipe Penginapan --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700 mb-2">Tipe Penginapan:</label>
                        <select name="tipe" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Kota:</label>
                        <select name="kota" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Periode:</label>
                        <select name="periode" class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none">
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
                        <label class="block text-sm font-medium text-gray-700 mb-2">Pencarian:</label>
                        <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penginapan..." 
                               class="w-full px-4 py-3 border border-gray-300 rounded-lg bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                    </div>
                    
                    {{-- Button Pencarian --}}
                    <div class="md:col-span-4 flex gap-4 justify-center mt-4">
                        <button type="submit" 
                                class="bg-orange-500 hover:bg-orange-600 text-white font-medium py-3 px-8 rounded-lg transition-colors duration-200 flex items-center">
                            <svg class="w-5 h-5 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Pencarian
                        </button>
                        <a href="{{ route('penginapan.list') }}" 
                           class="bg-gray-500 hover:bg-gray-600 text-white font-medium py-3 px-6 rounded-lg transition-colors duration-200">
                            Reset Filter
                        </a>
                    </div>
                </form>

                {{-- Advanced Filter Toggle --}}
                <div class="mt-6 border-t pt-6">
                    <button type="button" id="toggleAdvanced" 
                            class="text-teal-600 hover:text-teal-700 font-medium flex items-center">
                        <span>Filter Lanjutan</span>
                        <svg class="w-4 h-4 ml-2 transform transition-transform" id="advancedArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                        </svg>
                    </button>
                    
                    {{-- Advanced Filters --}}
                    <div id="advancedFilters" class="hidden mt-4 p-4 bg-gray-50 rounded-lg">
                        <form method="GET" action="{{ route('penginapan.list') }}" class="grid grid-cols-1 md:grid-cols-3 gap-4">
                            
                            {{-- Preserve other filters --}}
                            @foreach(request()->except(['harga_min', 'harga_max']) as $key => $value)
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endforeach
                            
                            {{-- Price Range --}}
                            <div class="md:col-span-2">
                                <label class="block text-sm font-medium text-gray-700 mb-2">Rentang Harga:</label>
                                <div class="grid grid-cols-2 gap-2">
                                    <input type="number" name="harga_min" value="{{ request('harga_min') }}" 
                                           placeholder="Harga minimum" 
                                           class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                    <input type="number" name="harga_max" value="{{ request('harga_max') }}" 
                                           placeholder="Harga maksimum" 
                                           class="px-3 py-2 border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                </div>
                            </div>
                            
                            {{-- Submit Advanced --}}
                            <div class="flex items-end">
                                <button type="submit" 
                                        class="w-full bg-teal-600 hover:bg-teal-700 text-white py-2 px-4 rounded-lg transition-colors duration-200">
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
        <div class="mx-auto max-w-7xl px-6">
            
            {{-- Active Filters Display --}}
            @if(request()->hasAny(['tipe', 'kota', 'harga_min', 'harga_max', 'periode', 'fasilitas', 'search']))
                <div class="mb-6 p-4 bg-white rounded-lg shadow-sm">
                    <div class="flex items-center justify-between">
                        <h3 class="text-sm font-medium text-gray-700">Filter Aktif:</h3>
                        <a href="{{ route('penginapan.list') }}" class="text-sm text-red-600 hover:text-red-700">Hapus Semua Filter</a>
                    </div>
                    <div class="mt-2 flex flex-wrap gap-2">
                        @if(request('tipe'))
                            <span class="bg-blue-100 text-blue-800 px-3 py-1 rounded-full text-sm">
                                Tipe: {{ request('tipe') }}
                            </span>
                        @endif
                        @if(request('kota'))
                            <span class="bg-green-100 text-green-800 px-3 py-1 rounded-full text-sm">
                                Kota: {{ request('kota') }}
                            </span>
                        @endif
                        @if(request('periode'))
                            <span class="bg-purple-100 text-purple-800 px-3 py-1 rounded-full text-sm">
                                Periode: {{ request('periode') }}
                            </span>
                        @endif
                        @if(request('search'))
                            <span class="bg-yellow-100 text-yellow-800 px-3 py-1 rounded-full text-sm">
                                Pencarian: "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('harga_min') || request('harga_max'))
                            <span class="bg-orange-100 text-orange-800 px-3 py-1 rounded-full text-sm">
                                Harga: Rp {{ number_format(request('harga_min', 0)) }} - Rp {{ number_format(request('harga_max', 999999999)) }}
                            </span>
                        @endif
                        @if(request('fasilitas'))
                            @foreach((array)request('fasilitas') as $fasilitasId)
                                @php
                                    $fasilitas = $all_fasilitas->find($fasilitasId);
                                @endphp
                                @if($fasilitas)
                                    <span class="bg-indigo-100 text-indigo-800 px-3 py-1 rounded-full text-sm">
                                        {{ $fasilitas->nama }}
                                    </span>
                                @endif
                            @endforeach
                        @endif
                    </div>
                </div>
            @endif
            
            {{-- Results Header --}}
            <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-8">
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
                        @foreach(request()->except(['sort_by', 'sort_order']) as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach
                        
                        <span class="text-gray-600">Sort by:</span>
                        <select name="sort_by" onchange="this.form.submit()" 
                                class="px-4 py-2 border border-gray-300 rounded-lg bg-white focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                            <option value="created_at" {{ request('sort_by') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                            <option value="harga" {{ request('sort_by') == 'harga' ? 'selected' : '' }}>Terlama</option>
                            <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Termurah</option>
                            <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Favorit</option>
                            <option value="rating" {{ request('sort_by') == 'rating' ? 'selected' : '' }}>Rekomendasi</option>
                        </select>
                    </form>
                </div>
            </div>

            {{-- Grid Penginapan --}}
            <div class="grid grid-cols-1 md:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 gap-6">
                @forelse ($penginapan as $index => $item)
                    <div class="bg-white rounded-2xl shadow-lg overflow-hidden hover:shadow-xl transition-shadow duration-300 group">
                        
                        {{-- Image Container - CORRECTED --}}
                        <div class="relative overflow-hidden">
                            <a href="{{ route('penginapan.detail', $item) }}">
                                @if($item->gambar && $item->gambar->count() > 0)
                                    <img src="{{ asset('storage/' . $item->primary_image) }}" 
                                         alt="{{ $item->nama }}"
                                         class="w-full h-48 object-cover group-hover:scale-105 transition-transform duration-300">
                                @else
                                    <div class="w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200 flex items-center justify-center">
                                        <svg class="w-16 h-16 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z"></path>
                                        </svg>
                                    </div>
                                @endif
                            </a>
                            
                            {{-- Rekomendasi Badge --}}
                            @if($index < 8) {{-- Show badge for first 8 items --}}
                                <div class="absolute top-4 left-4">
                                    <div class="bg-orange-500 text-white px-3 py-1 rounded-lg text-sm font-medium flex items-center">
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
                            <h3 class="text-xl font-bold text-gray-800 mb-3 group-hover:text-teal-600 transition-colors">
                                <a href="{{ route('penginapan.detail', $item) }}">{{ $item->nama }}</a>
                            </h3>
                            {{-- Location --}}
                            <div class="flex items-center text-gray-600 mb-2">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-sm">{{ $item->kota }}</span>
                            </div>
                            
                            {{-- Type --}}
                            <div class="flex items-center text-gray-600 mb-2">
                                <svg class="w-4 h-4 mr-2" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                <span class="text-sm">{{ $item->tipe }}</span>
                            </div>

                             {{-- Price & Views Container --}}
<div class="flex items-center justify-between">

    {{-- Price dengan potongan miring --}}
    <div class="bg-teal-600 text-white px-4 py-2 clip-slant">
        <div class="flex flex-col leading-tight">
            <span class="text-sm">Harian</span>
            <span class="text-xl font-bold">
                Rp{{ number_format($item->harga, 0, ',', '.') }}
            </span>
        </div>
    </div>

    {{-- Views (kanan) --}}
    <div class="flex items-center space-x-1 text-gray-700 text-sm font-medium pr-3">
        <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 
                8.268 2.943 9.542 7-1.274 4.057-5.064 
                7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
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
                                <div class="flex items-center text-gray-600 mb-2">
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
                            
                            {{-- Fasilitas --}}
                            @if($item->fasilitas && $item->fasilitas->count() > 0)
                                <div class="mb-3">
                                    <div class="flex flex-wrap gap-1">
                                        @foreach($item->fasilitas->take(2) as $fasilitas)
                                            <span class="bg-gray-100 text-gray-600 px-2 py-1 rounded text-xs">
                                                {{ $fasilitas->nama }}
                                            </span>
                                        @endforeach
                                        @if($item->fasilitas->count() > 2)
                                            <span class="text-gray-500 text-xs">+{{ $item->fasilitas->count() - 2 }} lagi</span>
                                        @endif
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="col-span-full text-center py-12">
                        <div class="mb-6">
                            <svg class="mx-auto w-16 h-16 text-gray-300" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="text-xl font-semibold text-gray-700 mb-2">Tidak ada penginapan ditemukan</h3>
                        <p class="text-gray-500 mb-4">Coba ubah filter pencarian atau hapus beberapa kriteria</p>
                        <a href="{{ route('penginapan.list') }}" 
                           class="bg-teal-600 hover:bg-teal-700 text-white px-6 py-2 rounded-lg transition-colors">
                            Lihat Semua Penginapan
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if($penginapan->hasPages())
                <div class="mt-12 flex justify-center">
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