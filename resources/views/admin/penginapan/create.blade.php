<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white rounded-lg shadow-xl">

                <h1 class="mb-6 text-2xl font-bold">Tambah Artikel Penginapan Baru</h1>

                {{-- Menampilkan error validasi jika ada --}}
                @if ($errors->any())
                    <div class="mb-4">
                        <div class="font-medium text-red-600">Whoops! Ada yang salah dengan input Anda.</div>
                        <ul class="mt-3 text-sm text-red-600 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.penginapan.store') }}" method="POST" enctype="multipart/form-data"
                    class="space-y-6">
                    @csrf

                    {{-- Baris 1: Nama, Tipe, Kota --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-3">
                        <div>
                            <label for="nama" class="block text-sm font-medium text-gray-700">Nama Penginapan</label>
                            <input type="text" name="nama" id="nama" value="{{ old('nama') }}" required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="tipe" class="block text-sm font-medium text-gray-700">Tipe Penginapan</label>
                            <input type="text" name="tipe" id="tipe" value="{{ old('tipe') }}"
                                placeholder="Contoh: Villa, Hotel" required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="kota" class="block text-sm font-medium text-gray-700">Kota</label>
                            <input type="text" name="kota" id="kota" value="{{ old('kota') }}"
                                placeholder="Contoh: Yogyakarta" required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                    </div>

                    {{-- Baris 2: Deskripsi --}}
                    <div>
                        <label for="deskripsi" class="block text-sm font-medium text-gray-700">Deskripsi</label>
                        <textarea name="deskripsi" id="deskripsi" rows="5" required
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">{{ old('deskripsi') }}</textarea>
                    </div>

                    {{-- Baris 3: Harga & Periode --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="harga" class="block text-sm font-medium text-gray-700">Harga (Rp)</label>
                            <input type="number" name="harga" id="harga" value="{{ old('harga') }}" required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                        </div>
                        <div>
                            <label for="periode_harga" class="block text-sm font-medium text-gray-700">Periode
                                Harga</label>
                            <select name="periode_harga" id="periode_harga" required
                                class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                                <option value="Harian">Harian</option>
                                <option value="Mingguan">Mingguan</option>
                                <option value="Bulanan">Bulanan</option>
                                <option value="Tahunan">Tahunan</option>
                            </select>
                        </div>
                    </div>

                    {{-- Fasilitas --}}
                    <div>
                        <label class="block text-sm font-medium text-gray-700">Fasilitas</label>
                        <div class="grid grid-cols-2 mt-2 md:grid-cols-4 gap-x-4 gap-y-2">
                            @foreach ($fasilitas as $item)
                                <div class="flex items-start">
                                    <div class="flex items-center h-5">
                                        <input id="fasilitas-{{ $item->id }}" name="fasilitas[]" value="{{ $item->id }}"
                                            type="checkbox"
                                            class="w-4 h-4 text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                                    </div>
                                    <div class="ml-3 text-sm">
                                        <label for="fasilitas-{{ $item->id }}"
                                            class="font-medium text-gray-700">{{ $item->nama }}</label>
                                    </div>
                                </div>
                            @endforeach
                        </div>
                    </div>

                    {{-- Lokasi Google Maps --}}
                    <div>
                        <label for="lokasi" class="block text-sm font-medium text-gray-700">Lokasi (URL Embed Google
                            Maps)</label>
                        <input type="text" name="lokasi" id="lokasi" value="{{ old('lokasi') }}"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                            placeholder="https://www.google.com/maps/embed?pb=...">
                        <p class="mt-2 text-sm text-gray-500">Buka Google Maps > Cari Lokasi > Share > Embed a map >
                            Salin URL dari dalam atribut `src="..."`</p>
                    </div>

                    {{-- Upload Gambar --}}
                    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                        <div>
                            <label for="thumbnail" class="block text-sm font-medium text-gray-700">Gambar Utama
                                (Thumbnail)</label>
                            <input type="file" name="thumbnail" id="thumbnail" required
                                class="block w-full mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        </div>
                        <div>
                            <label for="gambar" class="block text-sm font-medium text-gray-700">Galeri Gambar (Bisa
                                pilih banyak)</label>
                            <input type="file" name="gambar[]" id="gambar" multiple
                                class="block w-full mt-1 text-sm text-gray-900 border border-gray-300 rounded-lg cursor-pointer bg-gray-50 focus:outline-none">
                        </div>
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex items-center justify-end space-x-4">
                        <a href="{{ route('admin.penginapan.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                            Simpan Artikel
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>