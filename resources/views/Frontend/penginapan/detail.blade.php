<x-guest-layout>
    {{-- Menitipkan style khusus untuk halaman ini ke layout utama --}}
    @push('styles')
        <style>
            /* Aturan CSS ini akan memaksa perataan teks dari Quill
             * untuk diterapkan, bahkan jika bertentangan dengan style 'prose'.
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

            /* ============================================
             * PERBAIKAN UTAMA: Mengatur ulang Margin Paragraf di Blok PROSE
             * Ini akan mengatasi jarak antar paragraf yang terlalu lebar.
             * ============================================ */
            .prose p {
                /* PAKSA MARGIN MENJADI NOL agar perataan horizontal di dalam satu paragraf tetap rapat */
                margin-top: 0 !important; 
                margin-bottom: 0 !important; 
                /* Mengatur jarak baris agar teks terlihat wajar, ini menjadi pemisah utama antar baris */
                line-height: 1.6; 
            }

            /* Menghilangkan margin pada paragraf pertama di blok prose untuk mencegah spasi ganda
               dengan elemen di atasnya, seperti H3 di tentang-section */
            .prose p + p {
                margin-top: 0 !important;
            }

            /* Menghilangkan margin atas pada paragraf pertama agar tidak ada spasi ekstra di awal deskripsi */
            .tentang-section .prose > p:first-child {
                margin-top: 0 !important;
            }

            /* ... (CSS di atasnya: .gallery-section, .main-image-container default, .thumbnail-gallery default) ... */

/* 💡 BARU: Kontainer Gambar Utama (Main Image) */
.main-image-container-wrapper {
    width: 100%;
    overflow: hidden;
}

.main-image-container {
    width: 100%;
    /* Rasio 16:9 di mobile/default */
    aspect-ratio: 16 / 9; 
    overflow: hidden;
    flex-shrink: 0;
}

/* ... (CSS untuk .thumbnail-item, .thumbnail-item img, dll.) ... */


/* ---------------------------------------------------- */
/* MEDIA QUERY: DESKTOP (md: 768px ke atas) */
/* ---------------------------------------------------- */
@media (min-width: 768px) {
    /* Hapus flex-direction: row. Tetap COLUMN untuk tampilan Single-Column. */
    .gallery-container {
        flex-direction: column; 
    }
    
    .main-image-container-wrapper {
        /* Tetap 100% lebar */
        width: 100%; 
    }

    .main-image-container {
        /* 💡 PENYESUAIAN: Kunci rasio 16/9, dan setel min-height yang optimal */
        aspect-ratio: 16 / 9; 
        min-height: 400px; /* Minimal tinggi yang pas untuk desktop */
    }

    .thumbnail-gallery {
        /* Thumbnail kembali ke tata letak grid, BUKAN sidebar */
        width: 100%;
        padding: 1rem 0; /* Sesuaikan padding */
        
        /* 💡 PERUBAHAN: Gunakan 6 kolom untuk thumbnail di desktop */
        grid-template-columns: repeat(6, 1fr); 
        gap: 0.5rem;
        
        /* Hapus properti sidebar */
        overflow-x: auto;
        overflow-y: hidden; 
        margin-left: 0;
        height: auto;
    }
}


/* ---------------------------------------------------- */
/* MEDIA QUERY: DESKTOP BESAR (lg: 1000px ke atas) */
/* ---------------------------------------------------- */
@media (min-width: 1000px) {
    .main-image-container-wrapper {
        width: 100%;
    }
    
    .main-image-container {
        /* 💡 PERUBAHAN: Sesuaikan min-height agar tidak terlalu besar */
        /* min-height 500px terlihat wajar di layar lebar */
        min-height: 500px; 
        aspect-ratio: 16 / 9;
    }
    
    .thumbnail-gallery {
        width: 100%;
        /* 💡 PERUBAHAN: Gunakan 8 kolom agar thumbnail lebih kecil dan banyak */
        grid-template-columns: repeat(8, 1fr);
    }
}
            /* ============================================
             * SECTION: TENTANG (DESKRIPSI)
             * ============================================ */
            .tentang-section {
                background-color: white;
                border-radius: 0.5rem;
                padding: 1.5rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                margin-bottom: 2rem;
            }

            .tentang-section h3 {
                font-size: 1.875rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .tentang-section .prose {
                line-height: 1.75;
                color: #374151;
            }
            
            /* CATATAN: Margin pada .tentang-section .prose p dihapus/diperbaiki di atas pada .prose p */

            .tentang-section .prose strong {
                font-weight: 600;
                color: #1f2937;
            }

            /* ============================================
             * SECTION: ALAMAT & PETA
             * ============================================ */
            .alamat-section {
                background-color: white;
                border-radius: 0.5rem;
                padding: 1.5rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                margin-bottom: 2rem;
            }

            .alamat-section h3 {
                font-size: 1.875rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
            }

            .alamat-text {
                color: #374151;
                margin-bottom: 1.5rem;
                padding: 1rem;
                background-color: #f9fafb;
                border-left: 4px solid #0d9488;
                border-radius: 0.25rem;
            }

            .map-container {
                overflow: hidden;
                border-radius: 0.5rem;
                margin-bottom: 1rem;
                box-shadow: 0 4px 6px rgba(0, 0, 0, 0.1);
            }

            .map-container iframe {
                width: 100%;
                height: 400px;
                border: none;
                display: block;
            }

            .map-button {
                display: inline-block;
                padding: 0.75rem 1.5rem;
                background-color: #0d9488;
                color: white;
                text-decoration: none;
                border-radius: 0.5rem;
                font-weight: 600;
                transition: background-color 0.3s ease;
            }

            .map-button:hover {
                background-color: #0f766e;
                text-decoration: none;
                color: white;
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

            /* ============================================
             * SECTION: INFORMASI PEMESANAN
             * ============================================ */
            .booking-section {
                background-color: white;
                border-radius: 0.5rem;
                padding: 2rem 1.5rem;
                box-shadow: 0 1px 3px rgba(0, 0, 0, 0.1);
                margin-bottom: 2rem;
            }

            .booking-section h3 {
                font-size: 1.5rem;
                font-weight: 700;
                color: #1f2937;
                margin-bottom: 1.5rem;
                display: flex;
                align-items: center;
                gap: 0.5rem;
                justify-content: center;
            }
            .booking-buttons {
                display: flex;
                flex-direction: column;
                gap: 1rem;
            }

            .booking-btn {
                padding: 1rem 1.5rem;
                border-radius: 0.5rem;
                text-decoration: none;
                font-weight: 600;
                font-size: 1rem;
                display: flex;
                align-items: center;
                justify-content: center;
                gap: 0.75rem;
                transition: all 0.3s ease;
                border: none;
                cursor: pointer;
                color: white;
            }

            .booking-btn-whatsapp {
                background-color: #25d366;
            }

            .booking-btn-whatsapp:hover {
                background-color: #1fa857;
            }

            .booking-btn-social {
                background-color: #ec1c7f;
            }

            .booking-btn-social:hover {
                background-color: #d41670;
            }
            @media (max-width: 768px) {
                .header-info {
                    align-items: flex-start;
                    gap: 0.5rem;
                }

                .thumbnail-gallery {
                    grid-template-columns: repeat(3, 1fr);
                }
                
                .map-container iframe {
                    height: 250px;
                }

                .gallery-section,
                .tentang-section,
                .alamat-section {
                    margin-bottom: 1.5rem;
                }

                .booking-btn {
                    padding: 0.875rem 1.25rem;
                    font-size: 0.95rem;
                }
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
                <div class="flex flex-wrap md:flex-nowrap items-center space-x-8">
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
            </div>

            <div class="grid grid-cols-1 gap-8 lg:grid-cols-4">
                {{-- Kolom Utama (Konten Kiri) --}}
                <div class="lg:col-span-3">
                    
                    {{-- =============================================== 
                        SECTION 1: GALLERY (TERPISAH)
                        =============================================== --}}
                    <section class="gallery-section">
                        <div class="gallery-container">
                            <x-penginapan.gallery :penginapan="$penginapan" />
                        </div>
                    </section>

                    {{-- =============================================== 
                        SECTION 2: TABEL HARGA
                        =============================================== --}}
                    @if(isset($penginapan->harga_weekend) || isset($penginapan->harga_high_season))
                    <div class="mb-8 p-6 bg-white rounded-lg shadow-sm overflow-x-auto">
                        <table class="price-table min-w-full">
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

                    {{-- =============================================== 
                        SECTION 3: TENTANG/DESKRIPSI (TERPISAH)
                        =============================================== --}}
                    <section class="tentang-section">
                        <h3>
                            <span class="text-teal-600">🏠</span> Tentang {{ $penginapan->nama }}
                        </h3>
                        <div class="prose max-w-none prose-gray">
                            {!! $penginapan->deskripsi !!}
                        </div>
                    </section>

                    {{-- =============================================== 
                        SECTION 4: FASILITAS
                        =============================================== --}}
                    <div class="mb-8 p-6 bg-white rounded-lg shadow-sm">
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

                    {{-- =============================================== 
                        SECTION 5: INFORMASI PEMESANAN
                        =============================================== --}}
                    <section class="booking-section">
                        <h3>
                            <svg class="w-6 h-6 text-teal-600" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M3 5a2 2 0 012-2h3.28a1 1 0 01.948.684l1.498 4.493a1 1 0 01-.502 1.21l-2.257 1.13a11.042 11.042 0 005.516 5.516l1.13-2.257a1 1 0 011.21-.502l4.493 1.498a1 1 0 01.684.949V19a2 2 0 01-2 2h-1C9.716 21 3 14.284 3 6V5z"></path>
                            </svg>
                            Informasi Pemesanan
                        </h3>
                        <div class="booking-buttons">
                            <a href="https://wa.me/{{ $penginapan->kontak_whatsapp ?? '6285600157547' }}?text=Halo, saya tertarik dengan {{ $penginapan->nama }}" 
                                target="_blank" 
                                class="booking-btn booking-btn-whatsapp">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M17.472 14.382c-.297-.149-1.758-.867-2.03-.967-.273-.099-.471-.148-.67.15-.197.297-.767.966-.94 1.164-.173.199-.347.223-.644.075-.297-.15-1.255-.463-2.39-1.475-.883-.788-1.48-1.761-1.653-2.059-.173-.297-.018-.458.13-.606.134-.133.298-.347.446-.52.149-.174.198-.298.298-.497.099-.198.05-.371-.025-.52-.075-.149-.669-1.612-.916-2.207-.242-.579-.487-.5-.669-.51-.173-.008-.371-.01-.57-.01-.198 0-.52.074-.792.372-.272.297-1.04 1.016-1.04 2.479 0 1.462 1.065 2.875 1.213 3.074.149.198 2.096 3.2 5.077 4.487.709.306 1.262.489 1.694.625.712.227 1.36.195 1.871.118.571-.085 1.758-.719 2.006-1.413.248-.694.248-1.289.173-1.413-.074-.124-.272-.198-.57-.347m-5.421 7.403h-.004a9.87 9.87 0 01-5.031-1.378l-.361-.214-3.741.982.998-3.648-.235-.374a9.86 9.86 0 01-1.51-5.26c.001-5.45 4.436-9.884 9.888-9.884 2.64 0 5.122 1.03 6.988 2.898a9.825 9.825 0 012.893 6.994c-.003 5.45-4.437 9.884-9.885 9.884m8.413-18.297A11.815 11.815 0 0012.05 0C5.495 0 .16 5.335.157 11.892c0 2.096.547 4.142 1.588 5.945L.057 24l6.305-1.654a11.882 11.882 0 005.683 1.448h.005c6.554 0 11.890-5.335 11.893-11.893A11.821 11.821 0 0020.885 3.488"/>
                                </svg>
                                Hubungi via WhatsApp
                            </a>
                            <a href="https://www.instagram.com/reel/DJGat3nT9VE/?utm_source=ig_web_copy_link"
                                class="booking-btn booking-btn-social">
                                <svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24">
                                    <path d="M23 3a10.9 10.9 0 01-3.14 1.53 4.48 4.48 0 00-7.86 3v1A10.66 10.66 0 013 4s-4 9 5 13a11.64 11.64 0 01-7 2s9 5 20 5a9.5 9.5 0 00-9-5.5c4.75 2.25 7-7 7-7"/>
                                </svg>
                                Kunjungi Social Media
                            </a>

                        </div>
                    </section>

                    {{-- =============================================== 
                        SECTION 6: ALAMAT & PETA 
                        =============================================== --}}
                    <section class="alamat-section">
                        <h3>
                            <span class="text-teal-600">📍</span> Alamat
                        </h3>
                        
                        @if(isset($penginapan->alamat))
                            <div class="alamat-text">
                                {{ $penginapan->alamat }}
                            </div>
                        @endif
                        
                        <div class="map-container">
                            <iframe src="{{ $penginapan->lokasi }}" allowfullscreen="" loading="lazy"></iframe>
                        </div>
                        
                        <a href="{{ $penginapan->lokasi }}" target="_blank" class="map-button">
                            BUKA DI GOOGLE MAPS <span>↗</span>
                        </a>
                    </section>
                </div>
                
                {{-- Sidebar --}}
                <x-penginapan.sidebar 
                    :penginapan="$penginapan" 
                    :penginapan-rekomendasi="$penginapan_rekomendasi" 
                />
            </div>
        </div>
    </div>
    
    {{-- Panggil Component Section Rekomendasi --}}
    <x-penginapan.rekomendasi-section :penginapan-rekomendasi="$penginapan_rekomendasi" />

</x-guest-layout>
