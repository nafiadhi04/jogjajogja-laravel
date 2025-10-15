<footer class="text-white bg-gray-900 w-full"> 
    {{-- Main Content Area --}}
    <div class="py-6 lg:py-12">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
            <div class="flex flex-row overflow-x-auto space-x-6 pb-4 md:space-x-0 md:grid md:grid-cols-2 lg:grid-cols-4 md:gap-6 lg:gap-8">
                
                {{-- KONTEN UTAMA FOOTER ANDA (4 kolom) --}}
                
                {{-- Logo dan Deskripsi (Menggunakan File Gambar + Teks Ditumpuk) --}}
<div class="flex-shrink-0 w-64 lg:w-auto">
    <div class="flex items-start mb-4">
        {{-- Mengambil Logo dari File PNG (asumsi logo utuh termasuk ikon) --}}
        <div class="flex items-center">
            <div class="w-32 h-13 mr-2"> 
                {{-- Gunakan logo file yang sudah Anda siapkan --}}
                <img src="{{ asset('images/jogjajogja-white.webp') }}" alt="Logo Jogja Jogja" class="w-full h-full">
            </div>
        </div>
    </div>
    
    {{-- Deskripsi dan lainnya tetap sama --}}
    <p class="mb-6 text-sm text-gray-400">
        Jogja-Jogja Merupakan Platform Media Informasi Yang Memuat
        Informasi Tentang Jogja Dan Segala Keindahannya
    </p>
    
    <div class="flex space-x-3">
        {{-- Social Media Icons --}}
        <a href="#" class="p-2 text-white transition bg-gray-700 rounded-full hover:bg-teal-600"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 2C8.686 2 8.275 2.012 6.985 2.083 4.673 2.21 3.012 3.874 2.885 6.185 2.815 7.475 2.828 7.886 2.828 12s.012 4.525.083 5.815c.127 2.31 1.79 3.973 4.102 4.101 1.29.07 1.701.083 5.013.083s3.723-.013 5.013-.083c2.311-.128 3.974-1.791 4.101-4.101.07-.129.083-1.7.083-5.013s-.013-3.723-.083-5.013c-.127-2.312-1.79-3.975-4.101-4.102C16.525 2.012 16.114 2 12 2zm0 2.2c3.2 0 3.58.01 4.85.074 1.76.096 2.766 1.096 2.862 2.858.064 1.27.074 1.65.074 4.85s-.01 3.58-.074 4.85c-.096 1.76-1.096 2.766-2.858 2.862-1.27.064-1.65.074-4.85.074s-3.58-.01-4.85-.074c-1.76-.096-2.766-1.096-2.862-2.858-.064-1.27-.074-1.65-.074-4.85s.01-3.58.074-4.85c.096-1.76 1.096-2.766 2.858-2.862C8.42 4.21 8.8 4.2 12 4.2zm0 2.95A4.85 4.85 0 1 0 12 16.8A4.85 4.85 0 0 0 12 7.15zm0 1.95a2.9 2.9 0 1 1 0 5.8a2.9 2.9 0 0 1 0-5.8zm6.5-1.55a1.1 1.1 0 1 0 0 2.2a1.1 1.1 0 0 0 0-2.2z"/></svg></a>
        <a href="#" class="p-2 text-white transition bg-gray-700 rounded-full hover:bg-teal-600"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M12 0c-6.627 0-12 5.373-12 12s5.373 12 12 12 12-5.373 12-12-5.373-12-12-12zm-2.895 18.258c-.14-.002-1.996-.068-2.736-.367-.741-.299-1.25-.97-1.391-1.922-.141-.952.128-1.98.666-2.914.538-.934 1.343-1.64 2.308-2.071 1.189-.533 2.793-.722 4.417-.578v-1.792c-.006-.01-.013-.02-.019-.03-.263-.448-.52-.897-.788-1.343-.242-.403-.526-.786-.807-1.169-.472-.638-1.002-1.157-1.574-1.555-.572-.399-1.22-.588-1.905-.588-.501 0-.961.082-1.381.246-.42.164-.78.406-1.08.726-.298.318-.54.708-.724 1.168-.186.46-.279.992-.279 1.599h-2.316c0-.98.243-1.896.729-2.748.487-.852 1.196-1.55 2.127-2.094 1.116-.653 2.458-.979 4.026-.979 1.567 0 2.909.326 4.025.979 1.116.653 1.956 1.488 2.52 2.506.565 1.018.829 2.158.791 3.421-.038 1.263-.339 2.478-.902 3.645-.563 1.167-1.343 2.152-2.339 2.955-.996.804-2.193 1.398-3.593 1.782-.008.002-.014.004-.022.006-1.401.383-2.798.575-4.184.575z"/></svg></a>
        <a href="#" class="p-2 text-white transition bg-gray-700 rounded-full hover:bg-teal-600"><svg class="w-5 h-5" fill="currentColor" viewBox="0 0 24 24"><path d="M23.498 6.186a3.016 3.016 0 0 0-2.122-2.136C19.505 3.545 12 3.545 12 3.545s-7.505 0-9.377.505A3.017 3.017 0 0 0 .502 6.186C0 8.07 0 12 0 12s0 3.93.502 5.814a3.016 3.016 0 0 0 2.122 2.136c1.871.505 9.376.505 9.376.505s7.505 0 9.377-.505a3.015 3.015 0 0 0 2.122-2.136C24 15.93 24 12 24 12s0-3.93-.502-5.814zM9.545 15.568V8.432L15.818 12l-6.273 3.568z"/></svg></a>
    </div>
</div>
                {{-- Layanan --}}
                <div class="flex-shrink-0 w-40 lg:w-auto">
                    <h4 class="mb-4 text-base font-semibold">Layanan</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="transition hover:text-white">Penginapan</a></li>
                        <li><a href="#" class="transition hover:text-white">Kuliner</a></li>
                        <li><a href="#" class="transition hover:text-white">Spot Wisata</a></li>
                        <li><a href="#" class="transition hover:text-white">Event</a></li>
                        <li><a href="#" class="transition hover:text-white">Artikel</a></li>
                        <li><a href="#" class="transition hover:text-white">Lifestyle</a></li>
                    </ul>
                </div>

                {{-- Kontak Kami --}}
                <div class="flex-shrink-0 w-64 lg:w-auto">
                    <h4 class="mb-4 text-base font-semibold">Kontak Kami</h4>
                    <div class="space-y-3 text-sm text-gray-400">
                        <div class="flex items-start">
                            <svg class="w-5 h-5 mt-0.5 mr-2 text-teal-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"/></svg>
                            <div>
                                <p>Jl. Kaliurang KM 6 No.43 Depok</p>
                                <p>Sleman Yogyakarta</p>
                            </div>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path d="M2 3a1 1 0 011-1h2.153a1 1 0 01.986.836l.74 3.683a1 1 0 01-.689 1.157l-1.9 1.076a11.536 11.536 0 006.066 6.066l1.076-1.9a1 1 0 011.157-.689l3.683.74A1 1 0 0118 18.847V18a1 1 0 01-1 1h-2.153a1 1 0 01-.986-.836l-.74-3.683a1 1 0 01.689-1.157l1.9-1.076a11.536 11.536 0 00-6.066-6.066l-1.076 1.9a1 1 0 01-1.157.689l-3.683-.74A1 1 0 013 4.153V3z" clip-rule="evenodd" fill-rule="evenodd"/></svg>
                            <p>0895392337544</p>
                        </div>
                        <div class="flex items-center">
                            <svg class="w-5 h-5 mr-2 text-teal-400 flex-shrink-0" fill="currentColor" viewBox="0 0 20 20"><path fill-rule="evenodd" d="M10 18a8 8 0 100-16 8 8 0 000 16zm1-12a1 1 0 10-2 0v4a1 1 0 00.293.707l2.828 2.829a1 1 0 101.414-1.414L11 9.586V6z" clip-rule="evenodd"/></svg>
                            <div>
                                <p>Mon – Sat: 8 Am – 5 Pm,</p>
                                <p>Sunday: <span class="text-red-400">CLOSED</span></p>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Menu Lainnya --}}
                <div class="flex-shrink-0 w-40 lg:w-auto">
                    <h4 class="mb-4 text-base font-semibold">Menu Lainnya</h4>
                    <ul class="space-y-2 text-sm text-gray-400">
                        <li><a href="#" class="transition hover:text-white">Tentang Kami</a></li>
                        <li><a href="#" class="transition hover:text-white">Kemitraan</a></li>
                        <li><a href="#" class="transition hover:text-white">Pasang Iklan</a></li>
                        <li><a href="#" class="transition hover:text-white">Syarat Dan Ketentuan</a></li>
                        <li><a href="#" class="transition hover:text-white">Artikel</a></li>
                        <li><a href="#" class="transition hover:text-white">Hubungi Kami</a></li>
                    </ul>
                </div>
            </div>
        </div>
    </div>
    
    {{-- Bottom Bar (Copyright) --}}
    <div class="py-4 border-t border-gray-800">
        <div class="mx-auto max-w-7xl px-4 lg:px-8">
             <div class="text-center text-sm text-gray-500">
                <p>Copyright ©JogjaJogja | All Right Reserved</p>
             </div>
        </div>
    </div>
</footer>