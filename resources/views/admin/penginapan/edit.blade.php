<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white rounded-lg shadow-xl">
                <h1 class="mb-6 text-2xl font-bold text-gray-800">Edit Artikel Penginapan</h1>

                {{-- Menampilkan error validasi --}}
                @if ($errors->any())
                    <div class="mb-4">
                        <div class="font-medium text-red-600">Whoops! Ada beberapa masalah dengan input Anda.</div>
                        <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.penginapan.update', $penginapan) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT') {{-- Method spoofing untuk update --}}
                    
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        {{-- Kolom Kiri --}}
                        <div class="space-y-6">
                            {{-- Nama Penginapan --}}
                            <div>
                                <label for="nama" class="block text-sm font-medium text-gray-700">Nama Penginapan</label>
                                <input type="text" name="nama" id="nama" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" value="{{ old('nama', $penginapan->nama) }}" required>
                            </div>
                            {{-- Deskripsi --}}
                            <div>
                                <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                                <textarea name="deskripsi" id="deskripsi" rows="5" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" required>{{ old('deskripsi', $penginapan->deskripsi) }}</textarea>
                            </div>
                            {{-- Lokasi --}}
                            <div>
                                <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi (URL Google Maps)</label>
                                <input type="text" name="lokasi" id="lokasi" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" value="{{ old('lokasi', $penginapan->lokasi) }}">
                            </div>
                        </div>

                        {{-- Kolom Kanan --}}
                        <div class="space-y-6">
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                                    <input type="number" name="harga" id="harga" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" value="{{ old('harga', $penginapan->harga) }}" required>
                                </div>
                                <div>
                                    <label for="periode_harga" class="block text-sm font-medium text-gray-700">Periode Harga</label>
                                    <select name="periode_harga" id="periode_harga" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" required>
                                        <option value="Harian" {{ old('periode_harga', $penginapan->periode_harga) == 'Harian' ? 'selected' : '' }}>Harian</option>
                                        <option value="Mingguan" {{ old('periode_harga', $penginapan->periode_harga) == 'Mingguan' ? 'selected' : '' }}>Mingguan</option>
                                        <option value="Bulanan" {{ old('periode_harga', $penginapan->periode_harga) == 'Bulanan' ? 'selected' : '' }}>Bulanan</option>
                                        <option value="Tahunan" {{ old('periode_harga', $penginapan->periode_harga) == 'Tahunan' ? 'selected' : '' }}>Tahunan</option>
                                    </select>
                                </div>
                            </div>
                            <div class="grid grid-cols-2 gap-6">
                                <div>
                                    <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe Penginapan</label>
                                    <input type="text" name="tipe" id="tipe" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" value="{{ old('tipe', $penginapan->tipe) }}" required>
                                </div>
                                <div>
                                    <label for="kota" class="block text-sm font-medium text-gray-700">Kota</label>
                                    <input type="text" name="kota" id="kota" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm" value="{{ old('kota', $penginapan->kota) }}" required>
                                </div>
                            </div>
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Fasilitas</label>
                                <div class="grid grid-cols-2 gap-4 mt-2">
                                    @foreach ($fasilitas as $item)
                                        <div class="flex items-start">
                                            <div class="flex items-center h-5">
                                                <input id="fasilitas-{{ $item->id }}" name="fasilitas[]" type="checkbox" value="{{ $item->id }}" class="w-4 h-4 text-indigo-600 border-gray-300 rounded"
                                                @if(in_array($item->id, $penginapan->fasilitas->pluck('id')->toArray())) checked @endif
                                                >
                                            </div>
                                            <div class="ml-3 text-sm">
                                                <label for="fasilitas-{{ $item->id }}" class="font-medium text-gray-700">{{ $item->nama }}</label>
                                            </div>
                                        </div>
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>
                     <div class="pt-6 mt-6 border-t border-gray-200">
                        <h3 class="text-lg font-medium text-gray-900">Ubah Gambar</h3>
                        <div class="grid grid-cols-1 gap-6 mt-4 md:grid-cols-2">
                            <div>
                                <label class="block text-sm font-medium text-gray-700">Thumbnail Saat Ini</label>
                                <img src="{{ asset('storage/thumbnails_penginapan/' . $penginapan->thumbnail) }}" class="object-cover h-32 mt-2 rounded-md">
                                <label for="thumbnail" class="block mt-4 text-sm font-medium text-gray-700">Ganti Thumbnail (Opsional)</label>
                                <input type="file" name="thumbnail" id="thumbnail" class="block w-full mt-1 text-sm text-gray-500 border border-gray-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                            </div>
                            <div>
                                <label for="gambar" class="block text-sm font-medium text-gray-700">Tambah Gambar Galeri (Opsional)</label>
                                <input type="file" name="gambar[]" id="gambar" multiple class="block w-full mt-1 text-sm text-gray-500 border border-gray-300 rounded-md file:mr-4 file:py-2 file:px-4 file:rounded-md file:border-0 file:text-sm file:font-semibold file:bg-indigo-50 file:text-indigo-700 hover:file:bg-indigo-100">
                                
                                <p class="mt-4 text-sm font-medium text-gray-700">Galeri Saat Ini</p>
                                <div class="grid grid-cols-3 gap-4 mt-2">
                                    @foreach($penginapan->gambar as $g)
                                        <img src="{{ asset('storage/galeri_penginapan/'.$g->path_gambar) }}" class="object-cover h-24 rounded-md">
                                    @endforeach
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="pt-5 mt-6">
                        <div class="flex justify-end">
                            <a href="{{ route('admin.penginapan.index') }}" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                                Batal
                            </a>
                            <button type="submit" class="px-4 py-2 ml-3 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                                Simpan Perubahan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>
