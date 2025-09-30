<x-app-layout>
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush

    {{-- Compact Edit Wisata (UI lebih padat, fungsional tetap sama) --}}
    <div class="py-8" x-data="formManager()">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 overflow-hidden bg-white rounded-lg shadow">
                <h1 class="mb-4 text-lg font-semibold text-gray-800">Edit Artikel Wisata: {{ $wisata->nama }}</h1>

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

                @if(session('success_gambar'))
                    <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded" role="alert">
                        {{ session('success_gambar') }}
                    </div>
                @endif

                <form action="{{ route('admin.wisata.update', $wisata) }}" method="POST" enctype="multipart/form-data"
                    class="space-y-4" @submit="prepareFormSubmit">
                    @csrf
                    @method('PUT')

                    {{-- Baris 1: Nama, Tipe, Kota --}}
                    <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                        <div>
                            <label for="nama" class="block text-xs font-medium text-gray-700">Nama Wisata</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama', $wisata->nama) }}" required
                                maxlength="100" class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                        </div>
                        <div>
                            <label for="tipe" class="block text-xs font-medium text-gray-700">Tipe Wisata</label>
                            <input type="text" name="tipe" id="tipe" value="{{ old('tipe', $wisata->tipe) }}" required
                                placeholder="Contoh: Alam, Budaya"
                                class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                        </div>
                        <div>
                            <label for="kota" class="block text-xs font-medium text-gray-700">Kota</label>
                            <select name="kota" id="kota" required
                                class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                                <option value="Yogyakarta" @selected(old('kota', $wisata->kota) == 'Yogyakarta')>
                                    Yogyakarta</option>
                                <option value="Sleman" @selected(old('kota', $wisata->kota) == 'Sleman')>Sleman</option>
                                <option value="Bantul" @selected(old('kota', $wisata->kota) == 'Bantul')>Bantul</option>
                                <option value="Gunungkidul" @selected(old('kota', $wisata->kota) == 'Gunungkidul')>
                                    Gunungkidul</option>
                                <option value="Kulon Progo" @selected(old('kota', $wisata->kota) == 'Kulon Progo')>Kulon
                                    Progo</option>
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
                        <div id="editor-container" class="h-64 mt-1">{!! old('deskripsi', $wisata->deskripsi) !!}</div>
                    </div>

                    {{-- Harga Tiket --}}
                    <div>
                        <label for="harga_tiket" class="block text-xs font-medium text-gray-700">Harga Tiket
                            (Rp)</label>
                        <input type="text" name="harga_tiket" id="harga_tiket"
                            value="{{ old('harga_tiket', $wisata->harga_tiket) }}" required inputmode="numeric"
                            oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                            class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                        <p class="mt-1 text-xs text-gray-500">Isi 0 jika gratis.</p>
                    </div>

                    {{-- Fasilitas (compact) --}}
                    <div>
                        <label class="block text-xs font-medium text-gray-700">Fasilitas</label>
                        <div class="grid grid-cols-2 mt-2 text-sm md:grid-cols-4 gap-x-3 gap-y-2">
                            @php $checkedFasilitas = old('fasilitas', $wisata->fasilitas->pluck('id')->toArray()); @endphp
                            @foreach ($fasilitas as $item)
                                <div class="flex items-start">
                                    <div class="flex items-center h-4">
                                        <input id="fasilitas-{{ $item->id }}" name="fasilitas[]" value="{{ $item->id }}"
                                            type="checkbox" class="w-4 h-4 text-indigo-600 border-gray-300 rounded"
                                            @if(is_array($checkedFasilitas) && in_array($item->id, $checkedFasilitas))
                                            checked @endif>
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
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $wisata->lokasi) }}"
                            class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm"
                            placeholder="https://www.google.com/maps/embed?pb=...">
                    </div>

                    {{-- Upload Gambar --}}
                    <div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2">
                        <div>
                            <label class="block text-xs font-medium text-gray-700">Thumbnail Saat Ini</label>
                            <img src="{{ asset('storage/' . $wisata->thumbnail) }}" alt="Thumbnail"
                                class="object-cover h-20 mt-2 rounded-sm">
                            <label for="thumbnail" class="block mt-3 text-xs font-medium text-gray-700">Ganti Thumbnail
                                (Opsional)</label>
                            <input type="file" name="thumbnail" id="thumbnail"
                                class="block w-full mt-1 text-xs text-gray-900 border border-gray-300 rounded cursor-pointer bg-gray-50">
                        </div>

                        <div>
                            <label class="block text-xs font-medium text-gray-700">Galeri Gambar</label>
                            <input type="file" name="gambar[]" x-ref="galleryInput" @change="handleFileSelection"
                                multiple class="hidden">
                            <button type="button" @click="triggerFileInput"
                                class="inline-flex items-center px-3 py-1 mt-1 text-sm font-medium text-white bg-blue-600 border border-transparent rounded shadow-sm hover:bg-blue-700">
                                Tambah Gambar...
                            </button>

                            <p class="mt-3 text-xs font-medium text-gray-700" x-show="galleryPreviews.length > 0">Gambar
                                Baru:</p>
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

                            <p class="mt-3 text-xs font-medium text-gray-700">Galeri Saat Ini</p>
                            <div class="grid grid-cols-3 gap-2 mt-2">
                                @forelse($wisata->gambar as $g)
                                    <div class="relative group">
                                        <img src="{{ asset('storage/' . $g->path_gambar) }}"
                                            class="object-cover h-20 rounded-sm">
                                        <a href="{{ route('admin.wisata.gambar.destroy', $g->id) }}"
                                            onclick="return confirm('Anda yakin ingin menghapus gambar ini?');"
                                            class="absolute p-1 text-white bg-red-600 rounded-full opacity-0 top-1 right-1 group-hover:opacity-100">
                                            <svg class="w-3 h-3" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M6 18L18 6M6 6l12 12"></path>
                                            </svg>
                                        </a>
                                    </div>
                                @empty
                                    <p class="col-span-3 text-xs text-gray-500">Tidak ada gambar galeri.</p>
                                @endforelse
                            </div>
                        </div>
                    </div>

                    {{-- Tombol aksi --}}
                    <div class="flex items-center justify-end pt-4 space-x-3">
                        <a href="{{ route('admin.wisata.index') }}"
                            class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-3 py-1 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded hover:bg-indigo-700">
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
                    galleryFiles: [],
                    galleryPreviews: [],
                    existingImages: @json($wisata->gambar || []),

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
                    },
                    deleteExistingImage(id, index) {
                        if (!confirm('Anda yakin ingin menghapus gambar ini?')) return;
                        fetch(`/admin/wisata/gambar/${id}`, {
                            method: 'DELETE',
                            headers: {
                                'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                                'Accept': 'application/json'
                            }
                        }).then(r => r.json()).then(data => {
                            if (data.success) {
                                this.existingImages.splice(index, 1);
                            }
                        }).catch(e => console.error(e));
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
                if (charCount) charCount.textContent = quill.getText().trim().length + ' / 5000';

                quill.on('text-change', function () {
                    let text = quill.getText().trim();
                    let length = text.length;
                    deskripsiInput.value = quill.root.innerHTML;
                    if (charCount) {
                        charCount.textContent = length + ' / 5000';
                        if (length > 5000) charCount.classList.add('text-red-500'); else charCount.classList.remove('text-red-500');
                    }
                });

                form.addEventListener('submit', function (e) {
                    if (quill.root.innerHTML === '<p><br></p>') { deskripsiInput.value = ''; } else { deskripsiInput.value = quill.root.innerHTML; }
                });
            });
        </script>
    @endpush
</x-app-layout>