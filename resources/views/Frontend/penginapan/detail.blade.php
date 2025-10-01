<x-guest-layout>
    {{-- Menitipkan style khusus untuk halaman ini ke layout utama --}}
    @push('styles')
        <style>
            /* Aturan CSS ini akan memaksa perataan teks dari Quill
                   untuk diterapkan, bahkan jika bertentangan dengan style 'prose'.
                */
            .prose .ql-align-center {
                text-align: center;
            }

            .prose .ql-align-right {
                text-align: right;
            }

            .prose .ql-align-justify {
                text-align: justify;
            }

            /* Custom styles sesuai website referensi */
            .price-table {
                border-collapse: collapse;
                width: 100%;
                margin: 1.5rem 0;
                border: 1px solid #e5e7eb;
            }

            .price-table th,
            .price-table td {
                border: 1px solid #e5e7eb;
                padding: 0.75rem 1rem;
                text-align: left;
            }

            .price-table th {
                background-color: #f8fafc;
                font-weight: 600;
                color: #374151;
            }

            .price-table td:last-child {
                font-weight: 600;
                color: #1f2937;
            }

            .thumbnail-gallery {
                display: grid;
                grid-template-columns: repeat(5, 1fr);
                gap: 0.5rem;
                margin-top: 1rem;
            }

            .thumbnail-item {
                aspect-ratio: 1;
                overflow: hidden;
                border-radius: 0.5rem;
                cursor: pointer;
                border: 2px solid transparent;
                transition: border-color 0.2s;
            }

            .thumbnail-item:hover,
            .thumbnail-item.active {
                border-color: #0d9488;
            }

            .thumbnail-item img {
                width: 100%;
                height: 100%;
                object-fit: cover;
            }

            .spot-strategis {
                background: white;
                border: 1px solid #e5e7eb;
                border-radius: 0.5rem;
                padding: 1rem;
                margin-top: 2rem;
            }

            .spot-item {
                display: flex;
                justify-content: space-between;
                align-items: center;
                padding: 0.75rem 0;
                border-bottom: 1px solid #f3f4f6;
                text-decoration: none;
                color: #374151;
                transition: background-color 0.2s;
            }

            .spot-item:last-child {
                border-bottom: none;
            }

            .spot-item:hover {
                background-color: #f9fafb;
                border-radius: 0.25rem;
            }

            .spot-distance {
                background-color: #0d9488;
                color: white;
                padding: 0.25rem 0.75rem;
                border-radius: 1rem;
                font-size: 0.75rem;
                font-weight: 500;
            }

            .whatsapp-btn {
                background-color: #25d366;
                color: white;
                padding: 0.75rem 1.5rem;
                border-radius: 0.5rem;
                text-decoration: none;
                display: inline-flex;
                align-items: center;
                gap: 0.5rem;
                font-weight: 600;
                transition: background-color 0.2s;
                width: 100%;
                justify-content: center;
                margin-top: 1rem;
            }

            .whatsapp-btn:hover {
                background-color: #22c55e;
                color: white;
            }

            .breadcrumb {
                display: flex;
                align-items: center;
                gap: 0.5rem;
                margin-bottom: 1.5rem;
                font-size: 0.875rem;
                color: #6b7280;
            }

            .breadcrumb a {
                color: #0d9488;
                text-decoration: none;
            }

            .breadcrumb a:hover {
                text-decoration: underline;
            }

            .header-info {
                display: flex;
                align-items: center;
                gap: 1rem;
                margin-top: 0.5rem;
                font-size: 0.875rem;
                color: #6b7280;
            }

            .header-info span {
                display: flex;
                align-items: center;
                gap: 0.25rem;
            }

            .villa-badge {
                background-color: #f3f4f6;
                padding: 0.25rem 0.75rem;
                border-radius: 0.25rem;
                font-size: 0.75rem;
                font-weight: 500;
            }
        </style>
    @endpush

    <div class="py-8 bg-gray-50">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            {{-- Breadcrumb --}}
            <div class="breadcrumb">
                <a href="{{ route('home') }}">Home</a>
                <span>></span>
                <a href="{{ route('penginapan.list') }}">Penginapan</a>
                <span>></span>
                <span>{{ $penginapan->nama }}</span>
            </div>

            {{-- Header Penginapan --}}
            <div class="mb-6">
                <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">{{ $penginapan->nama }}</h1>
                <div class="flex items-center space-x-8">
                <p class="mt-2 text-3xl font-bold text-teal-600">
                    Rp {{ number_format($penginapan->harga, 0, ',', '.') }}
                    <span class="text-lg font-normal text-gray-600">/ {{ $penginapan->periode_harga }}</span>
                </p>
                <div class="header-info">
                    <span>📍 {{ $penginapan->kota }}</span>
                    <span class="villa-badge">{{ $penginapan->tipe }}</span>
                    <span>👁️ {{ $penginapan->views }} View</span>
                </div>
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
                {{-- Kolom Utama (Konten Kiri) --}}
                <div class="lg:col-span-3">
                    <div class="p-6 bg-white rounded-lg shadow-sm">
                        
                        {{-- Galeri Gambar Utama --}}
<div x-data="{ 
    activeSlide: 0, 
    slides: {{ $penginapan->gambar->count() + 1 }},
    isDragging: false,
    startX: 0,
    currentX: 0,
    translateX: 0,
    threshold: 100,
    
    // Untuk thumbnail scroll
    isDraggingThumb: false,
    thumbStartX: 0,
    thumbScrollLeft: 0,
    
    setActiveSlide(index) {
        this.activeSlide = index;
        this.translateX = -this.activeSlide * 100;
        this.scrollToActiveThumbnail();
    },
    
    scrollToActiveThumbnail() {
        this.$nextTick(() => {
            const container = this.$refs.thumbContainer;
            const activeThumb = container.children[this.activeSlide];
            if (activeThumb && container) {
                const containerWidth = container.offsetWidth;
                const thumbLeft = activeThumb.offsetLeft;
                const thumbWidth = activeThumb.offsetWidth;
                const scrollLeft = thumbLeft - (containerWidth / 2) + (thumbWidth / 2);
                
                container.scrollTo({
                    left: scrollLeft,
                    behavior: 'smooth'
                });
            }
        });
    },
    
    startDrag(e) {
        this.isDragging = true;
        this.startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
        this.currentX = this.startX;
        document.body.style.userSelect = 'none';
    },
    
    onDrag(e) {
        if (!this.isDragging) return;
        
        e.preventDefault();
        this.currentX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
        const diffX = this.currentX - this.startX;
        
        const baseTranslate = -this.activeSlide * 100;
        this.translateX = baseTranslate + (diffX / window.innerWidth) * 100;
        
        // Resistance at boundaries
        if (this.activeSlide === 0 && diffX > 0) {
            this.translateX = baseTranslate + (diffX / window.innerWidth) * 20;
        } else if (this.activeSlide === this.slides - 1 && diffX < 0) {
            this.translateX = baseTranslate + (diffX / window.innerWidth) * 20;
        }
    },
    
    endDrag() {
        if (!this.isDragging) return;
        
        this.isDragging = false;
        document.body.style.userSelect = '';
        
        const diffX = this.currentX - this.startX;
        
        if (Math.abs(diffX) > this.threshold) {
            if (diffX > 0 && this.activeSlide > 0) {
                this.activeSlide--;
            } else if (diffX < 0 && this.activeSlide < this.slides - 1) {
                this.activeSlide++;
            }
        }
        
        this.translateX = -this.activeSlide * 100;
        this.scrollToActiveThumbnail();
    },
    
    // Thumbnail drag scroll functions
    startThumbDrag(e) {
        this.isDraggingThumb = true;
        const container = e.currentTarget;
        this.thumbStartX = e.type === 'mousedown' ? e.pageX : e.touches[0].pageX;
        this.thumbScrollLeft = container.scrollLeft;
    },
    
    onThumbDrag(e) {
        if (!this.isDraggingThumb) return;
        e.preventDefault();
        const container = e.currentTarget;
        const x = e.type === 'mousemove' ? e.pageX : e.touches[0].pageX;
        const walk = (x - this.thumbStartX) * 2;
        container.scrollLeft = this.thumbScrollLeft - walk;
    },
    
    endThumbDrag() {
        this.isDraggingThumb = false;
    },
    
    // Navigasi gambar
    nextSlide() {
        if (this.activeSlide < this.slides - 1) {
            this.activeSlide++;
            this.translateX = -this.activeSlide * 100;
            this.scrollToActiveThumbnail();
        }
    },
    
    prevSlide() {
        if (this.activeSlide > 0) {
            this.activeSlide--;
            this.translateX = -this.activeSlide * 100;
            this.scrollToActiveThumbnail();
        }
    }
}" class="mb-6">
    {{-- Gambar Utama --}}
    <div class="relative overflow-hidden rounded-lg shadow-md">
        <div class="cursor-grab"
             :class="{ 'cursor-grabbing': isDragging }"
             @mousedown="startDrag($event)"
             @mousemove="onDrag($event)" 
             @mouseup="endDrag()"
             @mouseleave="endDrag()"
             @touchstart="startDrag($event)"
             @touchmove="onDrag($event)"
             @touchend="endDrag()">
            <div class="flex transition-transform duration-300 ease-out"
                :class="{ 'transition-none': isDragging }"
                :style="{ transform: `translateX(${translateX}%)` }">
                {{-- Thumbnail sebagai slide pertama --}}
                <div class="flex-shrink-0 w-full">
                    <img src="{{ asset('storage/' . $penginapan->thumbnail) }}"
                        alt="{{ $penginapan->nama }}" 
                        class="object-cover w-full h-96"
                        draggable="false">
                </div>
                {{-- Loop untuk gambar galeri --}}
                @foreach($penginapan->gambar as $gambar)
                    <div class="flex-shrink-0 w-full">
                        <img src="{{ asset('storage/' . $gambar->path_gambar) }}"
                            alt="Galeri {{ $penginapan->nama }}" 
                            class="object-cover w-full h-96"
                            draggable="false">
                    </div>
                @endforeach
            </div>
        </div>
        
        {{-- Tombol Navigasi Gambar --}}
        <button @click="prevSlide()"
            :class="{ 'opacity-50 cursor-not-allowed': activeSlide === 0 }"
            class="absolute left-4 top-1/2 transform -translate-y-1/2 z-10 p-2 text-white transition-all duration-200 bg-black bg-opacity-60 rounded-full hover:bg-opacity-80 hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M15 19l-7-7 7-7"></path>
            </svg>
        </button>
        
        <button @click="nextSlide()"
            :class="{ 'opacity-50 cursor-not-allowed': activeSlide === slides - 1 }"
            class="absolute right-4 top-1/2 transform -translate-y-1/2 z-10 p-2 text-white transition-all duration-200 bg-black bg-opacity-60 rounded-full hover:bg-opacity-80 hover:scale-110">
            <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                    d="M9 5l7 7-7 7"></path>
            </svg>
        </button>
    </div>

    {{-- Thumbnail Gallery (Hidden Scrollbar) --}}
    <div class="relative mt-4">
        <div x-ref="thumbContainer"
             class="flex gap-2 overflow-x-auto pb-2 scroll-smooth cursor-grab scrollbar-hide"
             :class="{ 'cursor-grabbing': isDraggingThumb }"
             @mousedown="startThumbDrag($event)"
             @mousemove="onThumbDrag($event)"
             @mouseup="endThumbDrag()"
             @mouseleave="endThumbDrag()"
             @touchstart="startThumbDrag($event)"
             @touchmove="onThumbDrag($event)"
             @touchend="endThumbDrag()">
            {{-- Thumbnail pertama --}}
            <div class="flex-shrink-0 w-32 h-20 cursor-pointer rounded-lg overflow-hidden border-2 transition-all duration-200"
                 :class="activeSlide === 0 ? 'border-blue-500 opacity-100' : 'border-gray-300 opacity-60 hover:opacity-80'"
                 @click="setActiveSlide(0)">
                <img src="{{ asset('storage/' . $penginapan->thumbnail) }}" 
                     alt="Thumbnail" 
                     class="w-full h-full object-cover pointer-events-none"
                     draggable="false">
            </div>
            {{-- Loop thumbnail galeri --}}
            @foreach($penginapan->gambar as $index => $gambar)
                <div class="flex-shrink-0 w-32 h-20 cursor-pointer rounded-lg overflow-hidden border-2 transition-all duration-200"
                     :class="activeSlide === {{ $index + 1 }} ? 'border-blue-500 opacity-100' : 'border-gray-300 opacity-60 hover:opacity-80'"
                     @click="setActiveSlide({{ $index + 1 }})">
                    <img src="{{ asset('storage/' . $gambar->path_gambar) }}" 
                         alt="Thumbnail {{ $index + 1 }}"
                         class="w-full h-full object-cover pointer-events-none"
                         draggable="false">
                </div>
            @endforeach
        </div>
    </div>
</div>

<style>
    /* Hide scrollbar untuk Chrome, Safari dan Opera */
    .scrollbar-hide::-webkit-scrollbar {
        display: none;
    }
    
    /* Hide scrollbar untuk IE, Edge dan Firefox */
    .scrollbar-hide {
        -ms-overflow-style: none;  /* IE dan Edge */
        scrollbar-width: none;  /* Firefox */
    }
</style>

                        {{-- End Galeri Gambar Utama --}}

                        {{-- Tentang Penginapan --}}
                        <div class="mb-8">
                            <h3 class="mb-4 text-2xl font-bold text-gray-800">
                                <span class="text-teal-600">🏠</span> Tentang {{ $penginapan->nama }}
                            </h3>
                            <div class="prose max-w-none prose-gray">
                                {!! $penginapan->deskripsi !!}
                            </div>
                        </div>

                        {{-- Tabel Harga (jika ada data harga detail) --}}
                        @if(isset($penginapan->harga_weekend) || isset($penginapan->harga_high_season))
                        <div class="mb-8">
                            <table class="price-table">
                                <thead>
                                    <tr>
                                        <th>Periode</th>
                                        <th>Harga</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    <tr>
                                        <td>Weekdays</td>
                                        <td>Rp{{ number_format($penginapan->harga, 0, ',', '.') }}</td>
                                    </tr>
                                    @if(isset($penginapan->harga_weekend))
                                    <tr>
                                        <td>Weekend</td>
                                        <td>Rp{{ number_format($penginapan->harga_weekend, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    @if(isset($penginapan->harga_high_season))
                                    <tr>
                                        <td>High Season</td>
                                        <td>Rp{{ number_format($penginapan->harga_high_season, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                    @if(isset($penginapan->harga_peak_season))
                                    <tr>
                                        <td>Peak Season</td>
                                        <td>Rp{{ number_format($penginapan->harga_peak_season, 0, ',', '.') }}</td>
                                    </tr>
                                    @endif
                                </tbody>
                            </table>
                            <p class="mt-2 font-semibold text-gray-700">
                                Segera reservasi sekarang dan nikmati liburan istimewa di {{ $penginapan->kota }}.
                            </p>
                        </div>
                        @endif

                        {{-- Fasilitas --}}
                        <div class="mb-8">
                            <h3 class="mb-4 text-2xl font-bold text-gray-800">
                                <span class="text-teal-600">🏨</span> Fasilitas
                            </h3>
                            <div class="grid grid-cols-1 gap-2 md:grid-cols-2">
                                @forelse ($penginapan->fasilitas as $fasilitas)
                                    <div class="flex items-center p-2">
                                        <span class="mr-3">•</span>
                                        <span class="text-gray-700">{{ $fasilitas->nama }}</span>
                                    </div>
                                @empty
                                    <p class="text-gray-500">Informasi fasilitas tidak tersedia.</p>
                                @endforelse
                            </div>
                        </div>

                        {{-- Informasi Pemesanan --}}
                        <div class="bg-white border border-gray-200 rounded-xl p-6 text-center">
    <h3 class="mb-6 text-2xl font-bold text-gray-800 flex items-center justify-center">
        <svg class="w-6 h-6 mr-2 text-teal-600" fill="currentColor" viewBox="0 0 24 24">
            <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488" />
        </svg>
        Informasi Pemesanan
    </h3>

    <a href="https://wa.me/{{ $penginapan->kontak_whatsapp ?? '6285600157547' }}?text=Halo, saya tertarik dengan {{ $penginapan->nama }}" 
        target="_blank" 
        class="w-full bg-green-500 hover:bg-green-600 text-white font-bold py-4 px-6 rounded-lg transition duration-300 ease-in-out flex items-center justify-center space-x-2 shadow-lg">
        Hubungi via WhatsApp
    </a>
</div>
                        {{-- Peta Lokasi --}}
                        <div class="mb-8">
                            <h3 class="mb-4 text-2xl font-bold text-gray-800">
                                <span class="text-teal-600">📍</span> Alamat
                            </h3>
                            @if(isset($penginapan->alamat))
                                <p class="mb-4 text-gray-600">{{ $penginapan->alamat }}</p>
                            @endif
                            <div class="overflow-hidden rounded-lg aspect-w-16 aspect-h-9">
                                <iframe src="{{ $penginapan->lokasi }}" class="w-full h-96" allowfullscreen=""
                                    loading="lazy"></iframe>
                            </div>
                            <a href="{{ $penginapan->lokasi }}" target="_blank" 
                               class="inline-block px-4 py-2 mt-4 text-white transition bg-teal-600 rounded hover:bg-teal-700">
                                <span class="text-white">↗</span> BUKA DI GOOGLE MAPS
                            </a>
                        </div>
                    </div>
                </div>

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
                        </div>
                        </form>
                        
                        {{-- Rekomendasi Penginapan --}}
                        <div class="p-4 mt-6 bg-white border rounded-lg shadow-sm">
    <h4 class="mb-4 font-semibold text-gray-800">Rekomendasi Penginapan</h4>
    
    @if(isset($penginapan_rekomendasi) && $penginapan_rekomendasi->count() > 0)
        @foreach($penginapan_rekomendasi->take(4) as $rekomendasi)
        
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
            </div>
        </div>
    </div>
    </div>
    
{{-- Section Rekomendasi Penginapan --}}
<div class="py-12 bg-white">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="flex items-center justify-between mb-8">
            <div>
                <p class="text-lg font-medium text-teal-600">Rekomendasi Penginapan</p>
                <h2 class="text-3xl font-bold text-gray-900">Penginapan Pilihan Di Jogja</h2>
            </div>
            <a href="{{ route('penginapan.list') }}" class="px-6 py-2 text-white transition bg-teal-600 rounded-lg hover:bg-teal-700">
                LIHAT SEMUA →
            </a>
        </div>

        {{-- Carousel Rekomendasi --}}
        @php
            $slides = $penginapan_rekomendasi->chunk(4); // MEMBAGI KOLEKSI MENJADI GRUP 4 ITEM
            $maxSlide = $slides->count() > 0 ? $slides->count() - 1 : 0; // Hitung maxSlide berdasarkan jumlah chunk
        @endphp

        <div x-data="{
            currentSlide: 0,
            maxSlide: {{ $maxSlide }},
            isDragging: false,
            hasDragged: false,
            startX: 0,
            startY: 0,
            currentX: 0,
            currentY: 0,
            dragOffset: 0,
            containerWidth: 0,
            threshold: 80, // minimum drag distance to trigger slide change (increased)
            clickThreshold: 15, // minimum movement to consider as drag vs click (increased)
            verticalThreshold: 20, // maximum vertical movement to still consider horizontal drag
            dragStarted: false, // flag to track if drag actually started
            
            init() {
                this.updateContainerWidth();
                window.addEventListener('resize', () => this.updateContainerWidth());
            },
            
            updateContainerWidth() {
                this.containerWidth = this.$refs.container?.offsetWidth || window.innerWidth;
            },
            
            startDrag(e) {
                this.updateContainerWidth();
                this.isDragging = true;
                this.hasDragged = false;
                this.dragStarted = false;
                this.startX = e.type === 'mousedown' ? e.clientX : e.touches[0].clientX;
                this.startY = e.type === 'mousedown' ? e.clientY : e.touches[0].clientY;
                this.currentX = this.startX;
                this.currentY = this.startY;
                this.dragOffset = 0;
            },
            
            onDrag(e) {
                if (!this.isDragging) return;
                
                this.currentX = e.type === 'mousemove' ? e.clientX : e.touches[0].clientX;
                this.currentY = e.type === 'mousemove' ? e.clientY : e.touches[0].clientY;
                
                const deltaX = this.currentX - this.startX;
                const deltaY = this.currentY - this.startY;
                
                const absX = Math.abs(deltaX);
                const absY = Math.abs(deltaY);
                
                // Only start dragging if movement is significant and primarily horizontal
                if (!this.dragStarted) {
                    if (absX > this.clickThreshold && absX > absY && absY < this.verticalThreshold) {
                        this.dragStarted = true;
                        this.hasDragged = true;
                        e.preventDefault();
                        document.body.style.userSelect = 'none';
                        document.body.style.cursor = 'grabbing';
                    } else if (absY > this.verticalThreshold) {
                        // If vertical movement is too much, cancel drag
                        this.endDrag();
                        return;
                    }
                }
                
                // Only process drag if drag has actually started
                if (this.dragStarted) {
                    e.preventDefault();
                    this.dragOffset = deltaX;
                    
                    // Add resistance at boundaries
                    let resistance = 1;
                    if ((this.currentSlide === 0 && this.dragOffset > 0) || 
                        (this.currentSlide === this.maxSlide && this.dragOffset < 0)) {
                        resistance = 0.3;
                    }
                    
                    this.dragOffset *= resistance;
                }
            },
            
            endDrag(e) {
                if (!this.isDragging) return;
                
                this.isDragging = false;
                document.body.style.userSelect = '';
                document.body.style.cursor = '';
                
                // Only change slide if user actually dragged significantly
                if (this.dragStarted && this.hasDragged && Math.abs(this.dragOffset) > this.threshold) {
                    if (this.dragOffset > 0 && this.currentSlide > 0) {
                        this.currentSlide--;
                    } else if (this.dragOffset < 0 && this.currentSlide < this.maxSlide) {
                        this.currentSlide++;
                    }
                }
                
                // Reset all drag states
                this.dragOffset = 0;
                this.dragStarted = false;
                
                // Reset hasDragged after a short delay to allow click events to process
                setTimeout(() => {
                    this.hasDragged = false;
                }, 50); // increased delay
            },
            
            getTransform() {
                const baseTransform = -this.currentSlide * 100;
                const dragTransform = this.dragStarted && this.isDragging ? (this.dragOffset / this.containerWidth) * 100 : 0;
                return baseTransform + dragTransform;
            },
            
            nextSlide() {
                if (this.currentSlide < this.maxSlide) {
                    this.currentSlide++;
                }
            },
            
            prevSlide() {
                if (this.currentSlide > 0) {
                    this.currentSlide--;
                }
            },
            
            goToSlide(index) {
                this.currentSlide = index;
            }
        }" class="relative">
            
            {{-- Navigation Arrows --}}
            <button 
                @click="prevSlide()"
                :class="{ 'opacity-50 cursor-not-allowed': currentSlide === 0 }"
                class="absolute left-0 z-10 flex items-center justify-center w-10 h-10 text-white transition-all duration-200 transform -translate-y-1/2 bg-black rounded-full bg-opacity-60 top-1/2 hover:bg-opacity-80 hover:scale-110">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"></path>
                </svg>
            </button>
            
            <button 
                @click="nextSlide()"
                :class="{ 'opacity-50 cursor-not-allowed': currentSlide === maxSlide }"
                class="absolute right-0 z-10 flex items-center justify-center w-10 h-10 text-white transition-all duration-200 transform -translate-y-1/2 bg-black rounded-full bg-opacity-60 top-1/2 hover:bg-opacity-80 hover:scale-110">
                <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"></path>
                </svg>
            </button>

            <div class="overflow-hidden cursor-grab select-none" 
                 x-ref="container"
                 :class="{ 'cursor-grabbing': dragStarted }"
                 @mousedown="startDrag($event)"
                 @mousemove="onDrag($event)" 
                 @mouseup="endDrag()"
                 @mouseleave="endDrag()"
                 @touchstart="startDrag($event)"
                 @touchmove="onDrag($event)"
                 @touchend="endDrag()"
                 @dragstart.prevent>
                <div class="relative">
                    <div class="flex will-change-transform" 
                         :class="{ 'transition-transform duration-500 ease-out': !isDragging }"
                         :style="{ transform: `translateX(${getTransform()}%)` }">
                        {{-- Data rekomendasi diambil dari variabel $penginapan_rekomendasi --}}
                        @if($slides->count() > 0)
                            @foreach($slides as $slide_penginapan_group) {{-- LOOP UNTUK SETIAP SLIDE/GRUP --}}
                                <div class="flex-shrink-0 w-full">
                                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2 lg:grid-cols-4">
                                       @foreach($slide_penginapan_group as $penginapan) {{-- LOOP UNTUK ITEM DI DALAM SLIDE --}}
                                            <a href="{{ route('penginapan.detail', ['penginapan' => $penginapan->slug]) }}" 
                                               @click="if (hasDragged || dragStarted) { $event.preventDefault(); }"
                                               class="block">
                                                <div class="overflow-hidden bg-white rounded-lg shadow-md transition-transform duration-200 hover:shadow-lg hover:-translate-y-1">
                                                    <div class="relative">
                                                        @if(isset($penginapan->thumbnail))
                                                            <img src="{{ asset('storage/' . $penginapan->thumbnail) }}" alt="{{ $penginapan->nama }}"
                                                                class="object-cover w-full h-48 transition-transform duration-300 hover:scale-105"
                                                                draggable="false"
                                                                @dragstart.prevent>
                                                        @else
                                                            <div class="flex items-center justify-center w-full h-48 bg-gradient-to-br from-gray-100 to-gray-200">
                                                                <span class="text-gray-400">No Image</span>
                                                            </div>
                                                        @endif
                                                        @if($penginapan->is_rekomendasi)
                                                            <div class="absolute top-2 right-2">
                                                                <span class="px-2 py-1 text-xs font-medium text-white bg-orange-500 rounded">👍 Rekomendasi</span>
                                                            </div>
                                                        @endif
                                                    </div>
                                                    <div class="p-4">
                                                        <h3 class="text-lg font-semibold text-gray-900 mb-2">{{ $penginapan->nama }}</h3>
                                                        <div class="flex items-center mt-2 text-sm text-gray-600">
                                                            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                                                            </svg>
                                                            <span class="truncate">{{ $penginapan->kota }}</span>
                                                        </div>
                                                        <div class="flex items-center mt-1 text-sm text-gray-600">
                                                            <svg class="w-4 h-4 mr-1 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20">
                                                                <path d="M10.394 2.08a1 1 0 00-.788 0l-7 3a1 1 0 000 1.84L5.25 8.051a.999.999 0 01.356-.257l4-1.714a1 1 0 11.788 1.84L7.25 9.049l.394.17a1 1 0 00.788 0l7-3a1 1 0 000-1.84l-7-3zM3.31 9.397L5 10.12v4.102a8.969 8.969 0 00-1.05-.174 1 1 0 01-.89-.89 11.115 11.115 0 01.25-3.762zM9.3 16.573A9.026 9.026 0 007 14.935v-3.957l1.818.78a3 3 0 002.364 0l5.508-2.361a11.026 11.026 0 01.25 3.762 1 1 0 01-.89.89 8.968 8.968 0 00-5.35 2.524 1 1 0 01-1.4 0zM6 18a1 1 0 001-1v-2.065a8.935 8.935 0 00-2-.712V17a1 1 0 001 1z"/>
                                                            </svg>
                                                            <span class="truncate">{{ $penginapan->tipe  }}</span>
                                                        </div>
                                                        <div class="flex items-center justify-between mt-4">
    <div class="px-4 py-2 bg-teal-600 rounded-lg shadow-md">
        <div class="flex items-center">
            <p class="text-white text-sm font-medium">{{ $penginapan->periode_harga }}</p>
        </div>
        <p class="text-white text-lg font-bold">Rp{{ number_format($penginapan->harga, 0, ',', '.') }}</p>
    </div>
    <div class="flex items-center text-sm text-gray-500">
        <svg class="w-4 h-4 mr-1" fill="currentColor" viewBox="0 0 20 20">
            <path d="M10 12a2 2 0 100-4 2 2 0 000 4z"/>
            <path fill-rule="evenodd" d="M.458 10C1.732 5.943 5.522 3 10 3s8.268 2.943 9.542 7c-1.274 4.057-5.064 7-9.542 7S1.732 14.057.458 10zM14 10a4 4 0 11-8 0 4 4 0 018 0z" clip-rule="evenodd"/>
        </svg>
        <span>{{ $penginapan->views}}</span>
    </div>
</div>
                                                    </div>
                                                </div>
                                            </a>
                                        @endforeach
                                    </div>
                                </div>
                            @endforeach {{-- AKHIR DARI LOOP UNTUK SETIAP SLIDE/GRUP --}}
                        @else
                            <div class="flex-shrink-0 w-full text-center text-gray-500">
                                <p>Tidak ada penginapan yang direkomendasikan saat ini.</p>
                            </div>
                        @endif
                    </div>
                </div>
            </div>
            
            {{-- Slide Indicators --}}
            @if($slides->count() > 1)
                <div class="flex justify-center mt-6 space-x-2">
                    @for($i = 0; $i <= $maxSlide; $i++)
                        <button 
                            @click="goToSlide({{ $i }})"
                            :class="{ 'bg-teal-600 scale-110': currentSlide === {{ $i }}, 'bg-gray-300': currentSlide !== {{ $i }} }"
                            class="w-3 h-3 transition-all duration-300 rounded-full hover:bg-teal-500 hover:scale-105">
                        </button>
                    @endfor
                </div>
            @endif
        </div>
    </div>
</div>

    {{-- Footer --}}
<footer class="py-16 text-white bg-gray-800">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-4">
            {{-- Logo dan Deskripsi --}}
            <div class="lg:col-span-1">
                <div class="flex items-center mb-4">
                    <div class="w-12 h-12 mr-3">
                        <svg viewBox="0 0 48 48" fill="none" class="w-full h-full">
                            <path d="M24 4L8 14V34L24 44L40 34V14L24 4Z" stroke="currentColor" stroke-width="2" fill="rgba(255,255,255,0.1)"/>
                            <path d="M24 12L16 18V30L24 36L32 30V18L24 12Z" fill="currentColor"/>
                        </svg>
                    </div>
                    <div>
                        <h3 class="text-xl font-bold">jogja</h3>
                        <h3 class="text-xl font-bold">jogja</h3>
                    </div>
                </div>
                <p class="mb-6 text-gray-300">
                    Jogja-Jogja Merupakan Platform Media Informasi Yang Memuat
                    Informasi Tentang Jogja Dan Segala Keindahannya
                </p>

                {{-- Newsletter --}}
                <div class="flex mb-6">
                    <input type="email" placeholder="Enter your mail"
                           class="flex-1 px-4 py-2 text-gray-900 bg-white rounded-l focus:outline-none">
                    <button class="px-4 py-2 text-white bg-teal-600 rounded-r hover:bg-teal-700">
                        →
                    </button>
                </div>

                {{-- Social Media Icons --}}
                <div class="flex space-x-3">
                    <a href="#" class="p-2 text-white transition bg-gray-600 rounded-full hover:bg-teal-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M24 4.557c-.883.392-1.832.656-2.828.775 1.017-.609 1.798-1.574 2.165-2.724-.951.564-2.005.974-3.127 1.195-.897-.957-2.178-1.555-3.594-1.555-3.179 0-5.515 2.966-4.797 6.045-4.091-.205-7.719-2.165-10.148-5.144-1.29 2.213-.669 5.108 1.523 6.574-.806-.026-1.566-.247-2.229-.616-.054 2.281 1.581 4.415 3.949 4.89-.693.188-1.452.232-2.224.084.626 1.956 2.444 3.379 4.6 3.419-2.07 1.623-4.678 2.348-7.29 2.04 2.179 1.397 4.768 2.212 7.548 2.212 9.142 0 14.307-7.721 13.995-14.646.962-.695 1.797-1.562 2.457-2.549z"/>
                        </svg>
                    </a>
                    <a href="#" class="p-2 text-white transition bg-gray-600 rounded-full hover:bg-teal-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M22.46 6c-.77.35-1.6.58-2.46.69.88-.53 1.56-1.37 1.88-2.38-.83.5-1.75.85-2.72 1.05C18.37 4.5 17.26 4 16 4c-2.35 0-4.27 1.92-4.27 4.29 0 .34.04.67.11.98C8.28 9.09 5.11 7.38 3 4.79c-.37.63-.58 1.37-.58 2.15 0 1.49.75 2.81 1.91 3.56-.71 0-1.37-.2-1.95-.5v.03c0 2.08 1.48 3.82 3.44 4.21a4.22 4.22 0 0 1-1.93.07 4.28 4.28 0 0 0 4 2.98 8.521 8.521 0 0 1-5.33 1.84c-.34 0-.68-.02-1.02-.06C3.44 20.29 5.7 21 8.12 21 16 21 20.33 14.46 20.33 8.79c0-.19 0-.37-.01-.56.84-.6 1.56-1.36 2.14-2.23z"/>
                        </svg>
                    </a>
                    <a href="#" class="p-2 text-white transition bg-gray-600 rounded-full hover:bg-teal-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M12.017 0C5.396 0 .029 5.367.029 11.987c0 5.079 3.158 9.417 7.618 11.174-.105-.949-.199-2.403.041-3.439.219-.937 1.406-5.957 1.406-5.957s-.359-.72-.359-1.781c0-1.663.967-2.911 2.168-2.911 1.024 0 1.518.769 1.518 1.688 0 1.029-.653 2.567-.992 3.992-.285 1.193.6 2.165 1.775 2.165 2.128 0 3.768-2.245 3.768-5.487 0-2.861-2.063-4.869-5.008-4.869-3.41 0-5.409 2.562-5.409 5.199 0 1.033.394 2.143.889 2.749.099.120.112.225.085.347-.09.375-.293 1.199-.334 1.363-.053.225-.172.271-.402.165-1.495-.69-2.433-2.878-2.433-4.646 0-3.776 2.748-7.252 7.92-7.252 4.158 0 7.392 2.967 7.392 6.923 0 4.135-2.607 7.462-6.233 7.462-1.214 0-2.357-.629-2.748-1.378 0 0-.599 2.282-.744 2.840-.282 1.084-1.064 2.456-1.549 3.235C9.584 23.815 10.77 24.001 12.017 24.001c6.624 0 11.99-5.367 11.99-11.988C24.007 5.367 18.641.001 12.017.001z"/>
                        </svg>
                    </a>
                    <a href="#" class="p-2 text-white transition bg-gray-600 rounded-full hover:bg-teal-600">
                        <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                            <path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/>
                        </svg>
                    </a>
                </div>
            </div>

            {{-- Layanan --}}
            <div>
                <h4 class="mb-4 text-lg font-semibold">Layanan</h4>
                <ul class="space-y-2 text-gray-300">
                    <li><a href="#" class="transition hover:text-white">Penginapan</a></li>
                    <li><a href="#" class="transition hover:text-white">Kuliner</a></li>
                    <li><a href="#" class="transition hover:text-white">Spot Wisata</a></li>
                    <li><a href="#" class="transition hover:text-white">Event</a></li>
                    <li><a href="#" class="transition hover:text-white">Artikel</a></li>
                </ul>
            </div>

            {{-- Kontak Kami --}}
            <div>
                <h4 class="mb-4 text-lg font-semibold">Kontak Kami</h4>
                <div class="space-y-3 text-gray-300">
                    <div class="flex items-start">
                        <svg class="w-5 h-5 mt-1 mr-3 text-teal-400" fill="currentColor" viewBox="0 0 20 20">
                            <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/>
                        </svg>
                        <div>
                            <p>Jl. Kaliurang KM 6 No.43 Depok</p>
                            <p>Sleman Yogyakarta</p>
                        </div>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-teal-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <p>+62 822 6542 6620</p>
                    </div>
                    <div class="flex items-center">
                        <svg class="w-5 h-5 mr-3 text-teal-400" fill="currentColor" viewBox="0 0 20 20">
                            <path d="M2.003 5.884L10 9.882l7.997-3.998A2 2 0 0016 4H4a2 2 0 00-1.997 1.884z"/>
                            <path d="M18 8.118l-8 4-8-4V14a2 2 0 002 2h12a2 2 0 002-2V8.118z"/>
                        </svg>
                        <p>info@jogja-jogja.com</p>
                    </div>
                </div>
            </div>

            {{-- Jam Kerja --}}
            <div>
                <h4 class="mb-4 text-lg font-semibold">Jam Kerja</h4>
                <div class="space-y-3 text-gray-300">
                    <p>Senin-Jumat: 9.00 - 17.00</p>
                    <p>Sabtu: 9.00 - 15.00</p>
                    <p>Minggu: Tutup</p>
                </div>
            </div>
        </div>

        <hr class="my-8 border-gray-700">

        <div class="text-center text-gray-400">
            <p>&copy; 2024 Jogja-Jogja. All Rights Reserved.</p>
        </div>
    </div>
</x-guest-layout>