<x-app-layout>
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush

    {{-- Compact Add Penginapan (UI lebih padat, fungsional tetap sama) --}}
    <div class="py-8" x-data="formManager()">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 overflow-hidden bg-white rounded-lg shadow">
                <h1 class="mb-4 text-lg font-semibold text-gray-800">Tambah Artikel Penginapan Baru</h1>

                @if ($errors->any())
                    <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded" role="alert">
                        <span class="font-semibold">Whoops! Terjadi kesalahan pada input Anda:</span>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li class="text-xs">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Menambahkan @submit untuk mempersiapkan file sebelum dikirim --}}
                <form action="{{ route('admin.penginapan.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4" @submit="prepareFormSubmit">
                    @csrf

                    {{-- Baris 1: Nama, Tipe, Kota --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label for="nama" class="block text-xs font-medium text-gray-700">Nama Penginapan</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required maxlength="100"
                                class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                        </div>
                        <div>
                            <label for="tipe" class="block text-xs font-medium text-gray-700">Tipe Penginapan</label>
                            <select name="tipe" id="tipe" required
                                class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                                <option value="">Pilih Tipe</option>
                                <option value="Villa" @selected(old('tipe') == 'Villa')>Villa</option>
                                <option value="Hotel" @selected(old('tipe') == 'Hotel')>Hotel</option>
                                <option value="Guest House" @selected(old('tipe') == 'Guest House')>Guest House</option>
                                <option value="Homestay" @selected(old('tipe') == 'Homestay')>Homestay</option>
                                <option value="Losmen" @selected(old('tipe') == 'Losmen')>Losmen</option>
                            </select>
                        </div>
                        <div>
                            <label for="kota" class="block text-xs font-medium text-gray-700">Lokasi</label>
                            <select name="kota" id="kota" required
                                class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                                <option value="">Pilih Kota/Kabupaten</option>
                                <option value="Yogyakarta" @selected(old('kota') == 'Yogyakarta')>Yogyakarta</option>
                                <option value="Sleman" @selected(old('kota') == 'Sleman')>Sleman</option>
                                <option value="Bantul" @selected(old('kota') == 'Bantul')>Bantul</option>
                                <option value="Gunungkidul" @selected(old('kota') == 'Gunungkidul')>Gunungkidul</option>
                                <option value="Kulon Progo" @selected(old('kota') == 'Kulon Progo')>Kulon Progo</option>
                            </select>
                        </div>
                    </div>

                    {{-- Deskripsi dengan QuillJS (lebih pendek) --}}
                    <div>
                        <div class="flex items-center justify-between">
                            <label for="deskripsi" class="block text-xs font-medium text-gray-700">Deskripsi</label>
                            <span id="char-count" class="text-xs text-gray-500">0 / 5000</span>
                        </div>
                        <input type="hidden" name="deskripsi" id="deskripsi-input">
                        <div id="editor-container" class="h-64 mt-1">{!! old('deskripsi') !!}</div>
                    </div>

                    {{-- Baris 3: Harga & Periode --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
                        <div>
                            <label for="harga" class="block text-xs font-medium text-gray-700">Harga (Rp)</label>
                            <input type="text" name="harga" id="harga" value="{{ old('harga') }}" required
                                inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                                class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                        </div>
                        <div>
                            <label for="periode_harga" class="block text-xs font-medium text-gray-700">Periode
                                Harga</label>
                            <select name="periode_harga" id="periode_harga" required
                                class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                                <option value="Harian" @selected(old('periode_harga') == 'Harian')>Harian</option>
                                <option value="Mingguan" @selected(old('periode_harga') == 'Mingguan')>Mingguan</option>
                                <option value="Bulanan" @selected(old('periode_harga') == 'Bulanan')>Bulanan</option>
                                <option value="Tahunan" @selected(old('periode_harga') == 'Tahunan')>Tahunan</option>
                            </select>
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Fasilitas</label>
                        <div class="grid grid-cols-2 mt-2 text-sm md:grid-cols-4 gap-x-3 gap-y-2">
                            @foreach ($fasilitas as $item)
                                <div class="flex items-start">
                                    <div class="flex items-center h-4">
                                        <input id="fasilitas-{{ $item->id }}" name="fasilitas[]" value="{{ $item->id }}"
                                            type="checkbox" class="w-4 h-4 text-indigo-600 border-gray-300 rounded"
                                            @if(is_array(old('fasilitas')) && in_array($item->id, old('fasilitas'))) checked
                                            @endif>
                                    </div>
                                    <div class="ml-2 text-xs">
                                        <label for="fasilitas-{{ $item->id }}"
                                            class="font-medium text-gray-700">{{ $item->nama }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Lokasi Google Maps --}}
                    <div>
                        <label for="lokasi" class="block text-xs font-medium text-gray-700">URL Google Maps
                            (Opsional)</label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                            class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm"
                            placeholder="https://www.google.com/maps/embed?pb=...">
                        <p class="mt-2 text-xs text-gray-500">Buka Google Maps → Share → Embed a map → Salin URL dari
                            atribut <code class="text-xs">src="..."</code></p>
                    </div>

                    {{-- Upload Gambar --}}
                    <div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2">
                        <div>
                            <label for="thumbnail" class="block text-xs font-medium text-gray-700">Gambar Utama
                                (Thumbnail)</label>
                            <input type="file" name="thumbnail" id="thumbnail" required
                                class="block w-full mt-1 text-xs text-gray-900 border border-gray-300 rounded cursor-pointer bg-gray-50">
                        </div>
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Galeri Gambar</label>
                            {{-- Input file tersembunyi --}}
                            <input type="file" name="gambar[]" x-ref="galleryInput" @change="handleFileSelection"
                                multiple class="hidden">

                            {{-- Tombol Kustom --}}
                            <button type="button" @click="triggerFileInput"
                                class="inline-flex items-center px-3 py-1 mt-1 text-sm font-medium text-white bg-blue-600 border border-transparent rounded shadow-sm hover:bg-blue-700">
                                Pilih File Gambar...
                            </button>

                            {{-- Area Pratinjau Gambar --}}
                            <p class="mt-3 text-xs font-medium text-gray-700" x-show="galleryPreviews.length > 0">
                                Gambar yang akan diupload:
                            </p>
                            <div class="grid grid-cols-3 gap-2 mt-2">
                                <template x-for="(preview, index) in galleryPreviews" :key="index">
                                    <div class="relative group">
                                        <img :src="preview" class="object-cover h-20 rounded-sm">
                                        <button type="button" @click="removeStagedFile(index)"
                                            class="absolute p-1 text-white bg-red-600 rounded-full top-1 right-1">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </button>
                                    </div>
                                </template>
                            </div>
                        </div>
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="flex items-center justify-end pt-4 space-x-3">
                        <a href="{{ route('admin.penginapan.index') }}"
                            class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-3 py-1 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded hover:bg-indigo-700">
                            Simpan Artikel
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
                galleryFiles: [],
                galleryPreviews: [],
                triggerFileInput() { this.$refs.galleryInput.click(); },
                handleFileSelection(event) {
                    for (let i = 0; i < event.target.files.length; i++) {
                        const file = event.target.files[i];
                        this.galleryFiles.push(file);
                        const reader = new FileReader();
                        reader.onload = (e) => { this.galleryPreviews.push(e.target.result); };
                        reader.readAsDataURL(file);
                    }
                    event.target.value = null;
                },
                removeStagedFile(index) {
                    this.galleryFiles.splice(index, 1);
                    this.galleryPreviews.splice(index, 1);
                },
                prepareFormSubmit() {
                    const dataTransfer = new DataTransfer();
                    this.galleryFiles.forEach(file => { dataTransfer.items.add(file); });
                    this.$refs.galleryInput.files = dataTransfer.files;
                }
            }
        }
        document.addEventListener('DOMContentLoaded', function () {
            var quill = new Quill('#editor-container', {
                theme: 'snow',
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
                let text = quill.root.innerHTML;
                let length = text.length;
                deskripsiInput.value = text;
                if (charCount) {
                    charCount.textContent = length + ' / 5000';
                    if (length > 5000) { charCount.classList.add('text-red-500'); } else { charCount.classList.remove('text-red-500'); }
                }
            });

            form.addEventListener('submit', function (e) {
                if (quill.root.innerHTML === '<p><br></p>') { deskripsiInput.value = ''; } else { deskripsiInput.value = quill.root.innerHTML; }
            });

            function selectLocalImage() {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.click();

                input.onchange = () => {
                    const file = input.files[0];
                    if (/^image\//.test(file.type)) {
                        saveToServer(file);
                    } else {
                        alert('Anda hanya bisa mengupload file gambar.');
                    }
                };
            }

            function saveToServer(file) {
                const fd = new FormData();
                fd.append('image', file);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                fetch("{{ route('admin.wisata.upload.image') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: fd
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.url) {
                            insertToEditor(result.url);
                        } else {
                            alert('Upload gagal: ' + (result.message || 'Error tidak diketahui'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengupload gambar.');
                    });
            }

            function insertToEditor(url) {
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', url);
                quill.setSelection(range.index + 1);
            }

            quill.getModule('toolbar').addHandler('image', () => {
                selectLocalImage();
            });
        });
    </script>
@endpush
</x-app-layout>