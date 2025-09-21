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
        </style>
    @endpush

    <div class="py-12 bg-gray-50">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="grid grid-cols-1 gap-12 lg:grid-cols-3">

                {{-- Kolom Utama (Konten Kiri) --}}
                <div class="lg:col-span-2">
                    <div class="p-8 bg-white rounded-lg shadow-lg">
                        {{-- Header Artikel --}}
                        <div>
                            <h1 class="text-4xl font-extrabold tracking-tight text-gray-900">{{ $penginapan->nama }}
                            </h1>
                            <div class="flex items-center mt-3 space-x-2 text-sm text-gray-500">
                                <span
                                    class="px-2 py-1 text-xs font-medium text-indigo-800 bg-indigo-100 rounded-full">{{ $penginapan->tipe }}</span>
                                <span>di {{ $penginapan->kota }}</span>
                                <span class="text-gray-300">·</span>
                                <span>Dilihat {{ $penginapan->views }} kali</span>
                            </div>
                        </div>

                        {{-- Galeri Gambar (Carousel) --}}
                        <div x-data="{ activeSlide: 0, slides: {{ $penginapan->gambar->count() + 1 }} }"
                            class="relative mt-6 overflow-hidden rounded-lg shadow-md">
                            {{-- Kontainer Gambar --}}
                            <div class="flex transition-transform duration-500 ease-in-out"
                                :style="{ transform: `translateX(-${activeSlide * 100}%)` }">
                                {{-- Thumbnail sebagai slide pertama --}}
                                <div class="flex-shrink-0 w-full">
                                    <img src="{{ asset('storage/' . $penginapan->thumbnail) }}"
                                        alt="{{ $penginapan->nama }}" class="object-cover w-full h-96">
                                </div>
                                {{-- Loop untuk gambar galeri --}}
                                @foreach($penginapan->gambar as $gambar)
                                    <div class="flex-shrink-0 w-full">
                                        <img src="{{ asset('storage/' . $gambar->path_gambar) }}"
                                            alt="Galeri {{ $penginapan->nama }}" class="object-cover w-full h-96">
                                    </div>
                                @endforeach
                            </div>
                            {{-- Tombol Navigasi Carousel --}}
                            <div class="absolute inset-0 flex items-center justify-between px-4">
                                <button @click="activeSlide = (activeSlide - 1 + slides) % slides"
                                    class="p-2 text-white transition bg-black bg-opacity-50 rounded-full hover:bg-opacity-75">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 19l-7-7 7-7"></path>
                                    </svg>
                                </button>
                                <button @click="activeSlide = (activeSlide + 1) % slides"
                                    class="p-2 text-white transition bg-black bg-opacity-50 rounded-full hover:bg-opacity-75">
                                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M9 5l7 7-7 7"></path>
                                    </svg>
                                </button>
                            </div>
                        </div>

                        {{-- Deskripsi --}}
                        <div class="mt-8">
                            <h3 class="mb-3 text-2xl font-bold text-gray-800">Deskripsi</h3>
                            {{-- Kelas 'prose' dari Tailwind Typography akan otomatis menata styling --}}
                            {{-- Penggunaan {!! !!} akan me-render tag HTML dengan benar --}}
                            <div class="prose max-w-none prose-indigo">
                                {!! $penginapan->deskripsi !!}
                            </div>
                        </div>

                        {{-- Peta Lokasi --}}
                        <div class="mt-8">
                            <h3 class="mb-3 text-2xl font-bold text-gray-800">Lokasi</h3>
                            <div class="overflow-hidden rounded-lg aspect-w-16 aspect-h-9">
                                <iframe src="{{ $penginapan->lokasi }}" class="w-full h-full" allowfullscreen=""
                                    loading="lazy"></iframe>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Sidebar Informasi (Konten Kanan) --}}
                <div class="lg:col-span-1">
                    <div class="sticky top-8">
                        <div class="p-6 bg-white rounded-lg shadow-lg">
                            <p class="text-3xl font-bold text-gray-900">
                                Rp {{ number_format($penginapan->harga, 0, ',', '.') }}
                                <span class="text-base font-normal text-gray-500">/
                                    {{ $penginapan->periode_harga }}</span>
                            </p>

                            <hr class="my-6">

                            <h5 class="text-lg font-semibold text-gray-800">Fasilitas</h5>
                            <ul class="mt-4 space-y-3">
                                @forelse ($penginapan->fasilitas as $fasilitas)
                                    <li class="flex items-center">
                                        <svg class="flex-shrink-0 w-5 h-5 text-green-500" fill="none" stroke="currentColor"
                                            viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                d="M5 13l4 4L19 7"></path>
                                        </svg>
                                        <span class="ml-3 text-gray-700">{{ $fasilitas->nama }}</span>
                                    </li>
                                @empty
                                    <li class="text-gray-500">Informasi fasilitas tidak tersedia.</li>
                                @endforelse
                            </ul>

                            <hr class="my-6">

                            <h6 class="text-lg font-semibold text-gray-800">Diposting oleh:</h6>
                            <div class="flex items-center mt-4">
                                <div>
                                    <p class="font-medium text-gray-900">{{ $penginapan->author->name }}</p>
                                    <p class="text-sm text-gray-500">{{ $penginapan->author->email }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </div>
    </div>
</x-guest-layout>