@props(['penginapan', 'penginapanRekomendasi'])

<div class="lg:col-span-1">
    <div class="sticky top-8 space-y-6">

        {{-- Spot Strategis Terdekat --}}
        @if(isset($penginapan->spot_terdekat) && $penginapan->spot_terdekat->count() > 0)
        <div x-data="{ open: false }" class="p-6 bg-white rounded-lg shadow-sm">
            <h3 class="mb-4 text-2xl font-bold text-gray-800">
                <span class="text-teal-600">📍</span> Spot Strategis Terdekat
            </h3>
            
            <div :class="{ 'max-h-56 overflow-hidden': !open, 'h-auto': open }" class="transition-all duration-300">
                @foreach($penginapan->spot_terdekat as $index => $spot)
                <a href="{{ $spot->maps_url }}" target="_blank" class="flex items-center justify-between p-4 mb-2 bg-gray-50 rounded-lg transition-colors duration-200 hover:bg-gray-100">
                    <div class="flex-1">
                        <span class="font-semibold text-gray-700">{{ $spot->nama }}</span>
                        <p class="text-sm text-gray-500">{{ $spot->waktu_tempuh }}</p>
                    </div>
                    <span class="ml-4 px-3 py-1 bg-blue-100 text-blue-800 text-xs font-medium rounded-full">{{ $spot->jarak }}</span>
                </a>
                @endforeach
            </div>

            @if($penginapan->spot_terdekat->count() > 3)
            <button @click="open = !open" class="w-full mt-2 text-sm text-teal-600 hover:text-teal-700 font-semibold">
                <span x-show="!open">▼ Tampilkan lebih banyak</span>
                <span x-show="open">▲ Tampilkan lebih sedikit</span>
            </button>
            @endif
        </div>
        @endif

        {{-- Pencarian Penginapan --}}
        <div class="p-4 mt-6 bg-white border rounded-lg shadow-sm">
            <h4 class="mb-3 font-semibold text-gray-800">Pencarian penginapan</h4>
            <form action="{{ route('penginapan.list') }}" method="GET">
                <div class="flex">
                    <input type="text" 
                    name="search" 
                    value="{{ request('search') }}" 
                    placeholder="Cari penginapan..." 
                    class="flex-1 px-3 py-2 border border-r-0 rounded-l focus:outline-none focus:border-teal-500">
                    <button class="px-4 py-2 text-white bg-teal-600 rounded-r hover:bg-teal-700">
                        🔍
                    </button>
                </div>
            </form>
        </div>
        
        {{-- Rekomendasi Penginapan --}}
        <div class="p-4 mt-6 bg-white border rounded-lg shadow-sm">
            <h4 class="mb-4 font-semibold text-gray-800">Rekomendasi Penginapan</h4>
            
            @if(isset($penginapanRekomendasi) && $penginapanRekomendasi->count() > 0)
                @foreach($penginapanRekomendasi->take(4) as $rekomendasi)
                
                    {{-- Tambahkan tautan ke halaman detail menggunakan slug --}}
                    <a href="{{ route('penginapan.detail', ['penginapan' => $rekomendasi->slug]) }}" 
                        class="flex mb-4 transition duration-150 ease-in-out hover:bg-gray-50 p-2 -mx-2 rounded-lg items-start">
                        
                        {{-- Gambar (Thumbnail) --}}
                        <img src="{{ asset('storage/' . $rekomendasi->thumbnail) }}" 
                            alt="{{ $rekomendasi->nama }}" 
                            class="object-cover w-16 h-12 rounded flex-shrink-0">
                        
                        {{-- Informasi Detail --}}
                        <div class="ml-3 truncate">
                            {{-- Nama Penginapan --}}
                            <h5 class="font-medium text-gray-900 truncate">{{ $rekomendasi->nama }}</h5>
                            
                            {{-- Harga dan Periode --}}
                            <p class="text-sm font-semibold text-teal-600">
                                Rp {{ number_format($rekomendasi->harga, 0, ',', '.') }} / {{ $rekomendasi->periode_harga }}
                            </p>
                            
                            {{-- Kota (Opsional, untuk info tambahan) --}}
                            <p class="text-xs text-gray-500">{{ $rekomendasi->kota }}</p>
                        </div>
                    </a>

                @endforeach
            @else
                <p class="text-sm text-gray-500">Tidak ada rekomendasi penginapan tersedia.</p>
            @endif
        </div>

        {{-- Author Info --}}
        <div class="p-4 mt-6 text-center text-white bg-gray-700 rounded-lg">
            <p class="text-sm">Author</p>
            <h4 class="text-lg font-semibold text-teal-400">{{ $penginapan->author->name }}</h4>
            <p class="mt-2 text-sm">📧 {{ $penginapan->author->email }}</p>
            @if(isset($penginapan->kontak_whatsapp))
            <p class="text-sm">📱 {{ $penginapan->kontak_whatsapp }}</p>
            @endif
        </div>
    </div>
</div>