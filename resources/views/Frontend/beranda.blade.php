<x-guest-layout>
    {{-- Hero Slider Section --}}
    <section class="relative h-screen" x-data="{ currentSlide: 0, totalSlides: 3 }" x-init="
        setInterval(() => {
            currentSlide = (currentSlide + 1) % totalSlides;
        }, 5000);
    ">
        {{-- Slide 1 --}}
        <div x-show="currentSlide === 0" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="absolute inset-0 bg-cover bg-center" 
             style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.0) 70%), url('https://images.unsplash.com/photo-1555400038-63f5ba517a47?w=1920');">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative h-full flex items-center justify-center text-center text-white px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-3 sm:mb-4 text-shadow-sm leading-tight">
                        Yogyakarta Life Guides And Services
                    </h1>
                    <p class="text-lg sm:text-xl md:text-2xl mb-6 sm:mb-8">
                        Satu Laman, Ribuan Cerita Wisata
                    </p>
                    <a href="{{ route('wisata.list') }}" 
                       class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 sm:px-8 py-3 rounded-full transition shadow-lg text-base sm:text-lg">
                        EXPLORE WISATA →
                    </a>
                </div>
            </div>
        </div>

        {{-- Slide 2 --}}
        <div x-show="currentSlide === 1" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="absolute inset-0 bg-cover bg-center" 
             style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.0) 70%), url('https://images.unsplash.com/photo-1537996194471-e657df975ab4?w=1920');">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative h-full flex items-center justify-center text-center text-white px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-3 sm:mb-4 text-shadow-sm leading-tight">
                        Temukan Penginapan Terbaik
                    </h1>
                    <p class="text-lg sm:text-xl md:text-2xl mb-6 sm:mb-8">
                        Pilihan Villa, Hotel, dan Homestay di Yogyakarta
                    </p>
                    <a href="{{ route('penginapan.list') }}" 
                       class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 sm:px-8 py-3 rounded-full transition shadow-lg text-base sm:text-lg">
                        LIHAT PENGINAPAN →
                    </a>
                </div>
            </div>
        </div>

        {{-- Slide 3 --}}
        <div x-show="currentSlide === 2" 
             x-transition:enter="transition ease-out duration-500"
             x-transition:enter-start="opacity-0"
             x-transition:enter-end="opacity-100"
             class="absolute inset-0 bg-cover bg-center" 
             style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.0) 70%), url('https://images.unsplash.com/photo-1506905925346-21bda4d32df4?w=1920');">
            <div class="absolute inset-0 bg-black/40"></div>
            <div class="relative h-full flex items-center justify-center text-center text-white px-4 sm:px-6 lg:px-8">
                <div class="max-w-4xl">
                    <h1 class="text-4xl sm:text-5xl md:text-6xl lg:text-7xl font-bold mb-3 sm:mb-4 text-shadow-sm leading-tight">
                        Jelajahi Keindahan Alam
                    </h1>
                    <p class="text-lg sm:text-xl md:text-2xl mb-6 sm:mb-8">
                        Destinasi Wisata Menakjubkan di Yogyakarta
                    </p>
                    <a href="{{ route('wisata.list') }}" 
                       class="inline-block bg-teal-600 hover:bg-teal-700 text-white font-semibold px-6 sm:px-8 py-3 rounded-full transition shadow-lg text-base sm:text-lg">
                        JELAJAHI SEKARANG →
                    </a>
                </div>
            </div>
        </div>

        {{-- Navigation Buttons --}}
        <button @click="currentSlide = (currentSlide - 1 + totalSlides) % totalSlides" 
                class="absolute left-2 sm:left-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 rounded-full p-2 sm:p-3 shadow-lg transition z-10">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M15 19l-7-7 7-7"/>
            </svg>
        </button>
        <button @click="currentSlide = (currentSlide + 1) % totalSlides" 
                class="absolute right-2 sm:right-4 top-1/2 -translate-y-1/2 bg-white/80 hover:bg-white text-gray-800 rounded-full p-2 sm:p-3 shadow-lg transition z-10">
            <svg class="w-5 h-5 sm:w-6 sm:h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7"/>
            </svg>
        </button>

        {{-- Dots Indicator --}}
        <div class="absolute bottom-6 sm:bottom-8 left-1/2 -translate-x-1/2 flex space-x-2 z-10">
            <button @click="currentSlide = 0" :class="currentSlide === 0 ? 'bg-white w-6 sm:w-8' : 'bg-white/50 w-2 sm:w-3'" class="h-2 sm:h-3 rounded-full transition-all"></button>
            <button @click="currentSlide = 1" :class="currentSlide === 1 ? 'bg-white w-6 sm:w-8' : 'bg-white/50 w-2 sm:w-3'" class="h-2 sm:h-3 rounded-full transition-all"></button>
            <button @click="currentSlide = 2" :class="currentSlide === 2 ? 'bg-white w-6 sm:w-8' : 'bg-white/50 w-2 sm:w-3'" class="h-2 sm:h-3 rounded-full transition-all"></button>
        </div>

        {{-- Floating Search Box --}}
        <div class="absolute bottom-0 left-1/2 -translate-x-1/2 translate-y-1/2 w-[95%] max-w-6xl px-0 sm:px-4 z-20" 
             x-data="{ activeTab: 'penginapan' }">
            <div class="bg-white rounded-xl sm:rounded-2xl shadow-2xl overflow-hidden">
                {{-- Tabs --}}
                <div class="flex border-b border-gray-200">
                    <button @click="activeTab = 'penginapan'" 
                            :class="activeTab === 'penginapan' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-600 hover:text-teal-600'"
                            class="flex-1 px-3 sm:px-6 py-3 sm:py-4 font-semibold transition-colors flex items-center justify-center gap-1 sm:gap-2 text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6"/>
                        </svg>
                        Penginapan
                    </button>
                    <button @click="activeTab = 'wisata'" 
                            :class="activeTab === 'wisata' ? 'text-teal-600 border-b-2 border-teal-600' : 'text-gray-600 hover:text-teal-600'"
                            class="flex-1 px-3 sm:px-6 py-3 sm:py-4 font-semibold transition-colors flex items-center justify-center gap-1 sm:gap-2 text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3.055 11H5a2 2 0 012 2v1a2 2 0 002 2 2 2 0 012 2v2.945M8 3.935V5.5A2.5 2.5 0 0010.5 8h.5a2 2 0 012 2 2 2 0 104 0 2 2 0 012-2h1.064M15 20.488V18a2 2 0 012-2h3.064M21 12a9 9 0 11-18 0 9 9 0 0118 0z"/>
                        </svg>
                        Wisata
                    </button>
                    <button class="flex-1 px-3 sm:px-6 py-3 sm:py-4 font-semibold text-gray-400 cursor-not-allowed flex items-center justify-center gap-1 sm:gap-2 text-sm sm:text-base">
                        <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M12 6v6m0 0v6m0-6h6m-6 0H6"/>
                        </svg>
                        Kuliner (Soon)
                    </button>
                </div>

                {{-- Penginapan Form (Disesuaikan untuk responsif) --}}
                <form x-show="activeTab === 'penginapan'" 
                      action="{{ route('penginapan.list') }}" 
                      method="GET"
                      class="p-4 sm:p-6">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                        {{-- Jenis Penginapan --}}
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Jenis Penginapan:</label>
                            <select name="tipe" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">Semua Jenis</option>
                                <option value="Villa">Villa</option>
                                <option value="Hotel">Hotel</option>
                                <option value="Homestay">Homestay</option>
                                <option value="Guest House">Guest House</option>
                            </select>
                        </div>

                        {{-- Periode --}}
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Periode:</label>
                            <select name="periode" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">Semua Periode</option>
                                <option value="Harian">Harian</option>
                                <option value="Mingguan">Mingguan</option>
                                <option value="Bulanan">Bulanan</option>
                            </select>
                        </div>

                        {{-- Pencarian --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Pencarian:</label>
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari nama atau lokasi..." 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>

                        {{-- Button --}}
                        <div class="col-span-2 md:col-span-1 flex items-end">
                            <button type="submit" 
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 sm:py-3 rounded-lg transition-colors shadow-md flex items-center justify-center gap-2 text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Pencarian
                            </button>
                        </div>
                    </div>
                </form>

                {{-- Wisata Form (Disesuaikan untuk responsif) --}}
                <form x-show="activeTab === 'wisata'" 
                      action="{{ route('wisata.list') }}" 
                      method="GET"
                      class="p-4 sm:p-6"
                      style="display: none;">
                    <div class="grid grid-cols-2 md:grid-cols-4 gap-3 sm:gap-4">
                        {{-- Jenis Wisata --}}
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Jenis Wisata:</label>
                            <select name="tipe" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">Semua Jenis</option>
                                <option value="Alam">Alam</option>
                                <option value="Budaya">Budaya</option>
                                <option value="Sejarah">Sejarah</option>
                                <option value="Edukasi">Edukasi</option>
                                <option value="Religi">Religi</option>
                            </select>
                        </div>

                        {{-- Harga --}}
                        <div>
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Harga:</label>
                            <select name="harga" class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">Semua Harga</option>
                                <option value="gratis">Gratis</option>
                                <option value="0-50000">< Rp 50.000</option>
                                <option value="50000-100000">Rp 50.000 - 100.000</option>
                                <option value="100000-999999">> Rp 100.000</option>
                            </select>
                        </div>

                        {{-- Pencarian --}}
                        <div class="col-span-2 md:col-span-1">
                            <label class="block text-xs sm:text-sm font-medium text-gray-700 mb-1 sm:mb-2">Pencarian:</label>
                            <input type="text" 
                                   name="search" 
                                   placeholder="Cari nama atau lokasi..." 
                                   class="w-full px-3 sm:px-4 py-2 sm:py-3 text-sm border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>

                        {{-- Button --}}
                        <div class="col-span-2 md:col-span-1 flex items-end">
                            <button type="submit" 
                                    class="w-full bg-orange-500 hover:bg-orange-600 text-white font-semibold px-6 py-2 sm:py-3 rounded-lg transition-colors shadow-md flex items-center justify-center gap-2 text-sm sm:text-base">
                                <svg class="w-4 h-4 sm:w-5 sm:h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"/>
                                </svg>
                                Pencarian
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </section>

    {{-- Section Penginapan (Tidak diubah, hanya memastikan data fetch) --}}
    @php
        $penginapanRekomendasi = \App\Models\Penginapan::where('status', 'diterima')
            ->with(['gambar'])
            ->orderByDesc('views')
            ->limit(12)
            ->get();
    @endphp
    
    @if($penginapanRekomendasi->count() > 0)
        <section id="penginapan" class="pt-20">
            @include('components.penginapan.rekomendasi-section', ['penginapanRekomendasi' => $penginapanRekomendasi])
        </section>
    @else
        <section id="penginapan" class="py-16 bg-white">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-gray-500">Tidak ada data penginapan yang tersedia saat ini.</p>
            </div>
        </section>
    @endif

    {{-- Section Wisata (Tidak diubah, hanya memastikan data fetch) --}}
    @php
        $wisataRekomendasi = \App\Models\Wisata::where('status', 'diterima')
            ->with(['gambar'])
            ->orderByDesc('views')
            ->limit(12)
            ->get();
    @endphp
    
    @if($wisataRekomendasi->count() > 0)
        <section id="wisata">
            @include('components.wisata.rekomendasi-section', ['wisataRekomendasi' => $wisataRekomendasi])
        </section>
    @else
        <section id="wisata" class="py-16 bg-gray-50">
            <div class="max-w-7xl mx-auto px-4 text-center">
                <p class="text-gray-500">Tidak ada data wisata yang tersedia saat ini.</p>
            </div>
        </section>
    @endif

    {{-- Section Coming Soon (Kuliner, Event, dll) --}}
    <section id="kuliner" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <div class="text-6xl mb-6">🍜</div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Kuliner</h2>
                <p class="text-gray-600 text-lg">Segera hadir! Temukan kuliner terbaik di Yogyakarta</p>
            </div>
        </div>
    </section>

    <section id="event" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <div class="text-6xl mb-6">📅</div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Event</h2>
                <p class="text-gray-600 text-lg">Segera hadir! Jangan lewatkan event menarik di Yogyakarta</p>
            </div>
        </div>
    </section>

    <section id="lifestyle" class="py-16 bg-white">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <div class="text-6xl mb-6">💝</div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Lifestyle</h2>
                <p class="text-gray-600 text-lg">Segera hadir! Inspirasi gaya hidup khas Yogyakarta</p>
            </div>
        </div>
    </section>

    <section id="artikel" class="py-16 bg-gray-50">
        <div class="max-w-7xl mx-auto px-4 text-center">
            <div class="max-w-2xl mx-auto">
                <div class="text-6xl mb-6">📰</div>
                <h2 class="text-3xl font-bold text-gray-900 mb-4">Artikel</h2>
                <p class="text-gray-600 text-lg">Segera hadir! Baca artikel menarik seputar Yogyakarta</p>
            </div>
        </div>
    </section>
</x-guest-layout>