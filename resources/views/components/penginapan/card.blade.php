@props(['penginapan', 'preventDrag' => false])

<div {{ $attributes->merge(['class' => 'block h-full']) }}>
    <div class="overflow-hidden bg-white rounded-lg shadow-md transition-transform duration-200 hover:shadow-lg hover:-translate-y-1 h-full cursor-default">
        
        {{-- Thumbnail - BISA DIKLIK --}}
        <div class="relative w-full h-48 overflow-hidden">
            <a href="{{ route('penginapan.detail', ['penginapan' => $penginapan->slug]) }}" 
                class="absolute inset-0 block cursor-pointer z-10"
                style="pointer-events: auto;"
                @if($preventDrag)
                    {{-- Mencegah klik jika geseran (drag) terjadi (untuk mobile) --}}
                    @click="if (hasDragged) { $event.preventDefault(); hasDragged = false; }"
                @endif
                {{-- ✅ SOLUSI DESKTOP: Mencegah mousedown event menyebar ke carousel, menghentikan pergeseran. --}}
                @mousedown.prevent
                >
                {{-- area klik hanya sesuai dengan elemen gambar --}}
            </a>
            
            @if(isset($penginapan->thumbnail))
                <img src="{{ asset('storage/' . $penginapan->thumbnail) }}" 
                    alt="{{ $penginapan->nama }}"
                    class="object-cover w-full h-full transition-transform duration-300 hover:scale-105 select-none"
                    draggable="false"
                    @dragstart.prevent>
            @else
                <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-gray-100 to-gray-200 select-none">
                    <span class="text-gray-400">No Image</span>
                </div>
            @endif
            
            @if($penginapan->is_rekomendasi)
                <div class="absolute top-2 right-2 pointer-events-none">
                    <span class="px-2 py-1 text-xs font-medium text-white bg-orange-500 rounded">
                        👍 Rekomendasi
                    </span>
                </div>
            @endif
        </div>
        
        {{-- Content --}}
        <div class="p-4">
            
            {{-- Nama - BISA DIKLIK HANYA TEKS --}}
            <h3 class="text-lg font-semibold text-gray-900 mb-2">
                <a href="{{ route('penginapan.detail', ['penginapan' => $penginapan->slug]) }}"
                    class="hover:text-teal-600 transition-colors cursor-pointer inline-block"
                    style="pointer-events: auto;"
                    @if($preventDrag)
                        {{-- Mencegah klik jika geseran (drag) terjadi (untuk mobile) --}}
                        @click="if (hasDragged) { $event.preventDefault(); hasDragged = false; }"
                    @endif
                    {{-- ✅ SOLUSI DESKTOP: Mencegah mousedown event menyebar ke carousel. --}}
                    @mousedown.stop
                    >
                    {{ $penginapan->nama }}
                </a>
            </h3>
            
            {{-- Location - TIDAK BISA DIKLIK --}}
            <div class="flex items-center mt-2 text-sm text-gray-600 select-none pointer-events-none">
                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                </svg>
                <span class="truncate">{{ $penginapan->kota }}</span>
            </div>
            
            {{-- Type - TIDAK BISA DIKLIK --}}
            <div class="flex items-center mt-1 text-sm text-gray-600 select-none pointer-events-none">
                <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                    <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.25 9.049l.394.17a1 1 0 00.788 0l7-3a1 1 0 000-1.84l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                </svg>
                <span class="truncate">{{ $penginapan->tipe }}</span>
            </div>
            
            {{-- Price & Views - TIDAK BISA DIKLIK --}}
            <div class="flex items-center justify-between mt-4 select-none pointer-events-none">
                <div class="px-4 py-2 bg-teal-600 rounded-lg shadow-md">
                    <div class="flex items-center">
                        <p class="text-white text-sm font-medium">{{ $penginapan->periode_harga }}</p>
                    </div>
                    <p class="text-white text-lg font-bold">
                        Rp{{ number_format($penginapan->harga, 0, ',', '.') }}
                    </p>
                </div>
                <div class="flex items-center text-sm text-gray-500">
                    <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
                        <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
                        <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
                    </svg>
                    <span>{{ $penginapan->views }}</span>
                </div>
            </div>
        </div>
    </div>
</div>