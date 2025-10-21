@php
    // $penginapan and $fasilitas expected to be passed when including this partial
@endphp

<div x-data="formManager()">
    <form action="{{ route('admin.penginapan.update', $penginapan) }}" method="POST" enctype="multipart/form-data"
        class="space-y-4" @submit="prepareFormSubmit">
        @csrf
        @method('PUT')

        {{-- Row 1: nama, tipe, kota --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-3">
            <div>
                <label for="nama" class="block text-xs font-medium text-gray-700">Nama Penginapan</label>
                <input type="text" name="nama" id="nama" value="{{ old('nama', $penginapan->nama) }}" required
                    maxlength="100" class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
            </div>
            <div>
                <label for="tipe" class="block text-xs font-medium text-gray-700">Tipe Penginapan</label>
                <select name="tipe" id="tipe" required
                    class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                    <option value="Villa" @selected(old('tipe', $penginapan->tipe) == 'Villa')>Villa</option>
                    <option value="Hotel" @selected(old('tipe', $penginapan->tipe) == 'Hotel')>Hotel</option>
                    <option value="Guest House" @selected(old('tipe', $penginapan->tipe) == 'Guest House')>Guest House
                    </option>
                    <option value="Homestay" @selected(old('tipe', $penginapan->tipe) == 'Homestay')>Homestay</option>
                    <option value="Losmen" @selected(old('tipe', $penginapan->tipe) == 'Losmen')>Losmen</option>
                </select>
            </div>
            <div>
                <label for="kota" class="block text-xs font-medium text-gray-700">Lokasi</label>
                <select name="kota" id="kota" required
                    class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                    <option value="">Pilih Kota/Kabupaten</option>
                    <option value="Yogyakarta" @selected(old('kota', $penginapan->kota) == 'Yogyakarta')>Yogyakarta</option>
                    <option value="Sleman" @selected(old('kota', $penginapan->kota) == 'Sleman')>Sleman</option>
                    <option value="Bantul" @selected(old('kota', $penginapan->kota) == 'Bantul')>Bantul</option>
                    <option value="Gunungkidul" @selected(old('kota', $penginapan->kota) == 'Gunungkidul')>Gunungkidul
                    </option>
                    <option value="Kulon Progo" @selected(old('kota', $penginapan->kota) == 'Kulon Progo')>Kulon Progo
                    </option>
                </select>
            </div>
        </div>

        {{-- Deskripsi (Quill) --}}
        <div>
            <div class="flex items-center justify-between">
                <label for="deskripsi" class="block text-xs font-medium text-gray-700">Deskripsi</label>
                <span id="char-count" class="text-xs text-gray-500">0 / 5000</span>
            </div>
            <input type="hidden" name="deskripsi" id="deskripsi-input">
            <div id="editor-container" class="h-64 mt-1">
                {!! old('deskripsi', $penginapan->deskripsi) !!}
            </div>
        </div>

        {{-- Harga & Periode --}}
        <div class="grid grid-cols-1 gap-4 md:grid-cols-2">
            <div>
                <label for="harga" class="block text-xs font-medium text-gray-700">Harga (Rp)</label>
                <input type="text" name="harga" id="harga" value="{{ old('harga', $penginapan->harga) }}" required
                    inputmode="numeric" oninput="this.value = this.value.replace(/[^0-9]/g, '');"
                    class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
            </div>
            <div>
                <label for="periode_harga" class="block text-xs font-medium text-gray-700">Periode Harga</label>
                <select name="periode_harga" id="periode_harga" required
                    class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm">
                    <option value="Harian" @selected(old('periode_harga', $penginapan->periode_harga) == 'Harian')>Harian
                    </option>
                    <option value="Mingguan" @selected(old('periode_harga', $penginapan->periode_harga) == 'Mingguan')>
                        Mingguan</option>
                    <option value="Bulanan" @selected(old('periode_harga', $penginapan->periode_harga) == 'Bulanan')>
                        Bulanan</option>
                    <option value="Tahunan" @selected(old('periode_harga', $penginapan->periode_harga) == 'Tahunan')>
                        Tahunan</option>
                </select>
            </div>
        </div>

        {{-- Fasilitas --}}
        <div>
            <label class="block text-xs font-medium text-gray-700">Fasilitas</label>
            <div class="grid grid-cols-2 mt-2 text-sm md:grid-cols-4 gap-x-3 gap-y-2">
                @php $checkedFasilitas = old('fasilitas', $penginapan->fasilitas->pluck('id')->toArray()); @endphp
                @foreach ($fasilitas as $item)
                    <div class="flex items-start">
                        <div class="flex items-center h-4">
                            <input id="fasilitas-{{ $item->id }}" name="fasilitas[]" value="{{ $item->id }}" type="checkbox"
                                class="w-4 h-4 text-indigo-600 border-gray-300 rounded" @if(is_array($checkedFasilitas) && in_array($item->id, $checkedFasilitas)) checked @endif>
                        </div>
                        <div class="ml-2 text-xs">
                            <label for="fasilitas-{{ $item->id }}"
                                class="font-medium text-gray-700">{{ $item->nama }}</label>
                        </div>
                    </div>
                @endforeach
            </div>
        </div>

        {{-- Lat / Long + Maps URL --}}
        <div class="grid grid-cols-1 gap-6 pt-6 mt-6 border-t md:grid-cols-2">
            <div>
                <label for="latitude" class="block text-sm font-medium text-gray-700">Latitude (Opsional)</label>
                <input type="text" name="latitude" id="latitude" value="{{ old('latitude', $penginapan->latitude) }}"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            </div>
            <div>
                <label for="longitude" class="block text-sm font-medium text-gray-700">Longitude (Opsional)</label>
                <input type="text" name="longitude" id="longitude"
                    value="{{ old('longitude', $penginapan->longitude) }}"
                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
            </div>
        </div>

        <div>
            <label for="lokasi" class="block text-xs font-medium text-gray-700">URL Google Maps (Opsional)</label>
            <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi', $penginapan->lokasi) }}"
                class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm" placeholder="https://...">
        </div>

        {{-- Upload gambar --}}
        <div class="grid grid-cols-1 gap-4 pt-4 mt-4 border-t md:grid-cols-2">
            <div>
                <label class="block text-xs font-medium text-gray-700">Thumbnail Saat Ini</label>
                <img src="{{ asset('storage/' . $penginapan->thumbnail) }}" alt="Thumbnail"
                    class="object-cover h-20 mt-2 rounded-sm">
                <label for="thumbnail" class="block mt-3 text-xs font-medium text-gray-700">Ganti Thumbnail
                    (Opsional)</label>
                <input type="file" name="thumbnail" id="thumbnail"
                    class="block w-full mt-1 text-xs text-gray-900 border border-gray-300 rounded cursor-pointer bg-gray-50">
            </div>

            <div>
                <label class="block text-xs font-medium text-gray-700">Galeri</label>

                <input type="file" name="gambar[]" x-ref="galleryInput" @change="handleFileSelection" multiple
                    class="hidden">

                <button type="button" @click="triggerFileInput"
                    class="inline-flex items-center px-3 py-1 mt-1 text-sm font-medium text-white bg-blue-600 border border-transparent rounded shadow-sm hover:bg-blue-700">
                    Pilih File Gambar...
                </button>

                <p class="mt-3 text-xs font-medium text-gray-700" x-show="galleryPreviews.length > 0">Gambar Baru (Siap
                    Diupload):</p>
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
                    @forelse($penginapan->gambar as $g)
                        <div class="relative group">
                            <img src="{{ asset('storage/' . $g->path_gambar) }}" class="object-cover h-20 rounded-sm">
                            <a href="{{ route('admin.penginapan.gambar.destroy', $g->id) }}"
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

        {{-- Buttons --}}
        <div class="flex items-center justify-end pt-4 space-x-3">
            <a href="{{ route('admin.penginapan.index') }}"
                class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">Batal</a>
            <button type="submit"
                class="px-3 py-1 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded hover:bg-indigo-700">Simpan
                Perubahan</button>
        </div>
    </form>
</div>