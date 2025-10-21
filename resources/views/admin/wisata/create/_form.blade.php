{{-- resources/views/admin/wisata/create/_form.blade.php --}}
<div class="py-8" x-data="formManager()">
    <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
        <div class="p-4 overflow-hidden bg-white rounded-lg shadow">
            <h1 class="mb-4 text-lg font-semibold text-gray-800">Tambah Artikel Wisata Baru</h1>

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

            <form action="{{ route('admin.wisata.store') }}" method="POST" enctype="multipart/form-data"
                class="space-y-4" @submit="prepareFormSubmit">
                @csrf

                {{-- Baris 1: Nama, Tipe, Kota --}}
                <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
                    <div>
                        <label for="nama" class="block text-xs font-medium text-gray-700">Nama Wisata</label>
                        <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required maxlength="100"
                            class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                    </div>
                    <div>
                        <label for="tipe" class="block text-xs font-medium text-gray-700">Tipe Wisata</label>
                        <input type="text" name="tipe" id="tipe" value="{{ old('tipe') }}" required
                            placeholder="Contoh: Alam, Budaya, Kuliner"
                            class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                    </div>
                    <div>
                        <label for="kota" class="block text-xs font-medium text-gray-700">Kota</label>
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

                {{-- Deskripsi dengan QuillJS --}}
                <div>
                    <div class="flex items-center justify-between">
                        <label for="deskripsi" class="block text-xs font-medium text-gray-700">Deskripsi</label>
                        <span id="char-count" class="text-xs text-gray-500">0 / 5000</span>
                    </div>
                    <input type="hidden" name="deskripsi" id="deskripsi-input">
                    <div id="editor-container" class="h-64 mt-1">{!! old('deskripsi') !!}</div>
                </div>

                {{-- Harga Tiket --}}
                <div>
                    <label for="harga_tiket" class="block text-xs font-medium text-gray-700">Harga Tiket
                        (Rp)</label>
                    <input type="text" name="harga_tiket" id="harga_tiket" value="{{ old('harga_tiket', 0) }}" required
                        inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                        class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                    <p class="mt-1 text-xs text-gray-500">Isi 0 jika gratis.</p>
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

                {{-- Lokasi / Maps --}}
                <div class="grid grid-cols-1 gap-6 pt-6 mt-6 border-t md:grid-cols-2">
                    <div>
                        <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude
                            (Opsional)</label>
                        <input type="text" name="latitude" id="latitude" value="{{ old('latitude') }}"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" placeholder="">
                    </div>
                    <div>
                        <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude
                            (Opsional)</label>
                        <input type="text" name="longitude" id="longitude" value="{{ old('longitude') }}"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" placeholder="">
                    </div>
                </div>

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
                        <input type="file" name="gambar[]" x-ref="galleryInput" @change="handleFileSelection" multiple
                            class="hidden">

                        <button type="button" @click="triggerFileInput"
                            class="inline-flex items-center px-3 py-1 mt-1 text-sm font-medium text-white bg-blue-600 border border-transparent rounded shadow-sm hover:bg-blue-700">
                            Pilih File Gambar...
                        </button>

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
                    <a href="{{ route('admin.wisata.index') }}"
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