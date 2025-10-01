<x-app-layout>
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush

    {{-- ========================================================== --}}
    {{-- PERUBAHAN UTAMA: Alpine.js sekarang mengelola state file --}}
    {{-- ========================================================== --}}
    <div class="py-12" x-data="formManager()">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white rounded-lg shadow-xl">
                <h1 class="mb-6 text-2xl font-bold text-gray-800">Edit Artikel: {{ $penginapan->nama }}</h1>

                @if ($errors->any())
                    <div class="p-4 mb-6 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                        <span class="font-bold">Whoops! Terjadi kesalahan pada input Anda:</span>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success_gambar'))
                    <div class="p-4 mb-6 text-sm text-green-700 bg-green-100 rounded-lg" role="alert">
                        {{ session('success_gambar') }}
                    </div>
                @endif

                {{-- Menambahkan @submit untuk mempersiapkan file sebelum dikirim --}}
                <form action="{{ route('admin.penginapan.update', $penginapan) }}" method="POST"
                    enctype="multipart/form-data" class="space-y-6" @submit="prepareFormSubmit">
                    @csrf
                    @method('PUT')

                    {{-- (Semua field input lain tidak berubah) --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Penginapan</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $penginapan->nama) }}"
                                required maxlength="100" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe Penginapan</label>
                            <select name="tipe" id="tipe" required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="Villa" @selected(old('tipe', $penginapan->tipe) == 'Villa')>Villa</option>
                                <option value="Hotel" @selected(old('tipe', $penginapan->tipe) == 'Hotel')>Hotel</option>
                                <option value="Guest House" @selected(old('tipe', $penginapan->tipe) == 'Guest House')>Guest House</option>
                                <option value="Homestay" @selected(old('tipe', $penginapan->tipe) == 'Homestay')>Homestay</option>
                                <option value="Losmen" @selected(old('tipe', $penginapan->tipe) == 'Losmen')>Losmen</option>
                            </select>
                        </div>
                        <div>
                            <label for="kota" class="block text-sm font-medium text-gray-700">Lokasi</label>
                            <select name="kota" id="kota" required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="">Pilih Kota/Kabupaten</option>
                                <option value="Kota Yogyakarta" @selected(old('kota', $penginapan->kota) == 'Kota Yogyakarta')>
                                    Kota Yogyakarta</option>
                                <option value="Sleman" @selected(old('kota', $penginapan->kota) == 'Sleman')>Sleman
                                </option>
                                <option value="Bantul" @selected(old('kota', $penginapan->kota) == 'Bantul')>Bantul
                                </option>
                                <option value="Gunungkidul" @selected(old('kota', $penginapan->kota) == 'Gunungkidul')>
                                    Gunungkidul</option>
                                <option value="Kulon Progo" @selected(old('kota', $penginapan->kota) == 'Kulon Progo')>
                                    Kulon Progo</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                            <span id="char-count" class="text-sm text-gray-500">0 / 5000</span>
                        </div>
                        <input type="hidden" name="deskripsi" id="deskripsi-input">
                        <div id="editor-container" class="mt-1 h-[500px]">
                            {!! old('deskripsi', $penginapan->deskripsi) !!}</div>
                    </div>
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                            <input type="text" name="harga" id="harga" value="{{ old('harga', $penginapan->harga) }}"
                                required inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                        </div>
                        <div>
                            <label for="periode_harga" class="block text-sm font-medium text-gray-700">Periode
                                Harga</label>
                            <select name="periode_harga" id="periode_harga" required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                <option value="Harian" @selected(old('periode_harga', $penginapan->periode_harga) == 'Harian')>Harian</option>
                                <option value="Mingguan" @selected(old('periode_harga', $penginapan->periode_harga) == 'Mingguan')>Mingguan</option>
                                <option value="Bulanan" @selected(old('periode_harga', $penginapan->periode_harga) == 'Bulanan')>Bulanan</option>
                                <option value="Tahunan" @selected(old('periode_harga', $penginapan->periode_harga) == 'Tahunan')>Tahunan</option>
                            </select>
                        </div>
                    </div>
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fasilitas</label>
                        <div class="grid grid-cols-2 mt-2 md:grid-cols-4 gap-x-4 gap-y-2">
                            @php $checkedFasilitas = old('fasilitas', $penginapan->fasilitas->pluck('id')->toArray()); @endphp
                            @foreach ($fasilitas as $item)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="fasilitas-{{ $item->id }}" name="fasilitas[]" value="{{ $item->id }}"
                                            type="checkbox" class="w-4 h-4 text-indigo-600 border-gray-300 rounded"
                                            @if(is_array($checkedFasilitas) && in_array($item->id, $checkedFasilitas))
                                            checked @endif>
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="fasilitas-{{ $item->id }}"
                                            class="font-medium text-gray-700">{{ $item->nama }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">URL Google Maps
                            (Opsional)</label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $penginapan->lokasi) }}"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                            placeholder="https://www.google.com/maps/embed?pb=...">
                    </div>

                    {{-- Upload Gambar --}}
                    <div class="grid grid-cols-1 gap-6 pt-6 mt-6 border-t md:grid-cols-2">
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Thumbnail Saat Ini</label>
                            <img src="{{ asset('storage/' . $penginapan->thumbnail) }}" alt="Thumbnail"
                                class="object-cover h-32 mt-2 rounded-md">
                            <label for="thumbnail" class="block mt-4 text-sm font-medium text-gray-700">Ganti Thumbnail
                                (Opsional)</label>
                            <input type="file" name="thumbnail" id="thumbnail"
                                class="block w-full mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-sm font-medium text-gray-700">Galeri</label>

                            {{-- Input file tersembunyi --}}
                            <input type="file" name="gambar[]" x-ref="galleryInput" @change="handleFileSelection"
                                multiple class="hidden">

                            {{-- Tombol Kustom --}}
                            <button type="button" @click="triggerFileInput"
                                class="inline-flex items-center px-4 py-2 mt-1 text-sm font-medium text-white bg-blue-600 border border-transparent rounded-md shadow-sm hover:bg-blue-700">
                                Pilih File Gambar...
                            </button>

                            {{-- Area Pratinjau Gambar Baru --}}
                            <p class="mt-4 text-sm font-medium text-gray-700" x-show="galleryPreviews.length > 0">
                                Gambar Baru (Siap Diupload):
                            </p>
                            <div class="grid grid-cols-3 gap-4 mt-2">
                                <template x-for="(preview, index) in galleryPreviews" :key="index">
                                    <div class="relative group">
                                        <img :src="preview" class="object-cover h-24 rounded-md">
                                        <button type="button" @click="removeStagedFile(index)"
                                            class="absolute p-1 leading-none text-white transition-opacity bg-red-600 rounded-full opacity-75 top-1 right-1 hover:opacity-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>

                            <p class="mt-4 text-sm font-medium text-gray-700">Galeri Saat Ini</p>
                            <div class="grid grid-cols-3 gap-4 mt-2">
                                @forelse($penginapan->gambar as $g)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $g->path_gambar) }}"
                                            class="object-cover h-24 rounded-md">
                                        <a href="{{ route('admin.penginapan.gambar.destroy', $g->id) }}"
                                            onclick="return confirm('Anda yakin ingin menghapus gambar ini?');"
                                            class="absolute p-1 leading-none text-white transition-opacity bg-red-600 rounded-full opacity-0 top-1 right-1 group-hover:opacity-100">
                                            <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @empty
                                    <p class="col-span-3 text-sm text-gray-500">Tidak ada gambar galeri.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    <div class="flex items-center justify-end pt-5 space-x-4">
                        <a href="{{ route('admin.penginapan.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>
        <script>
            function formManager() {
                return {
                    galleryFiles: [],      // Menyimpan objek File
                    galleryPreviews: [],   // Menyimpan data URL untuk pratinjau

                    // Method untuk memicu klik pada input file yang tersembunyi
                    triggerFileInput() {
                        this.$refs.galleryInput.click();
                    },

                    // Method untuk menangani saat pengguna memilih file
                    handleFileSelection(event) {
                        for (let i = 0; i < event.target.files.length; i++) {
                            const file = event.target.files[i];
                            this.galleryFiles.push(file);
                            const reader = new FileReader();
                            reader.onload = (e) => {
                                this.galleryPreviews.push(e.target.result);
                            };
                            reader.readAsDataURL(file);
                        }
                        event.target.value = null;
                    },

                    // Method untuk menghapus gambar dari antrian pratinjau
                    removeStagedFile(index) {
                        this.galleryFiles.splice(index, 1);
                        this.galleryPreviews.splice(index, 1);
                    },

                    // Method yang dijalankan tepat sebelum form disubmit
                    prepareFormSubmit() {
                        const dataTransfer = new DataTransfer();
                        this.galleryFiles.forEach(file => {
                            dataTransfer.items.add(file);
                        });
                        this.$refs.galleryInput.files = dataTransfer.files;
                    }
                }
            }

            document.addEventListener('DOMContentLoaded', function () {
                var quill = new Quill('#editor-container', {
                    theme: 'snow',
                    // ==========================================================
                    // PERBAIKAN UTAMA: Mengisi kembali opsi toolbar
                    // ==========================================================
                    modules: {
                        toolbar: [
                            [{ 'header': [1, 2, 3, false] }],
                            ['bold', 'italic', 'underline'],
                            [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                            [{ 'align': [] }],
                            ['link', 'image'],
                            ['clean']
                        ]
                    }
                });

                var deskripsiInput = document.querySelector('#deskripsi-input');
                var form = document.querySelector('form');
                var charCount = document.querySelector('#char-count');
                deskripsiInput.value = quill.root.innerHTML;
                if (charCount) {
                    charCount.textContent = quill.getText().trim().length + ' / 5000';
                }
                quill.on('text-change', function () {
                    let text = quill.getText().trim();
                    let length = text.length;
                    deskripsiInput.value = quill.root.innerHTML;
                    if (charCount) {
                        charCount.textContent = length + ' / 5000';
                        if (length > 5000) { charCount.classList.add('text-red-500'); } else { charCount.classList.remove('text-red-500'); }
                    }
                });
                form.addEventListener('submit', function (e) {
                    if (quill.root.innerHTML === '<p><br></p>') { deskripsiInput.value = ''; } else { deskripsiInput.value = quill.root.innerHTML; }
                });
            });
        </script>
    @endpush
</x-app-layout>