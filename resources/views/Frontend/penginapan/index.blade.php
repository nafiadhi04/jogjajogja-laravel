<x-guest-layout>
    {{-- Kita letakkan konten di dalam container standar layout --}}
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Bagian Header Halaman --}}
            <div class="mb-8 text-center">
                <h2 class="text-3xl font-extrabold text-gray-900">Daftar Penginapan di Yogyakarta</h2>
                <p class="mt-2 text-lg text-gray-600">Temukan tempat menginap terbaik untuk liburan Anda.</p>
            </div>

            {{-- Grid untuk menampilkan kartu penginapan --}}
            <div class="grid grid-cols-1 gap-8 md:grid-cols-2 lg:grid-cols-3">

                {{-- Gunakan @forelse untuk looping, ada @empty jika data kosong --}}
                @forelse ($all_penginapan as $item)
                    <div
                        class="flex flex-col overflow-hidden transition duration-300 transform bg-white rounded-lg shadow-lg hover:scale-105">

                        {{-- Gambar Thumbnail --}}
                        <div class="flex-shrink-0">
                            {{-- Menambahkan link pada gambar --}}
                            <a href="{{ route('penginapan.detail', $item->slug) }}">
                                <img class="object-cover w-full h-56"
                                    src="{{ asset('storage/thumbnails_penginapan/' . $item->thumbnail) }}"
                                    alt="{{ $item->nama }}">
                            </a>
                        </div>

                        {{-- Konten Kartu --}}
                        <div class="flex flex-col justify-between flex-1 p-6">
                            <div class="flex-1">
                                {{-- Tipe dan Kota --}}
                                <p class="text-sm font-medium text-indigo-600">
                                    {{ $item->tipe }} di {{ $item->kota }}
                                </p>
                                {{-- Nama Penginapan --}}
                                <a href="{{ route('penginapan.detail', $item->slug) }}" class="block mt-2">
                                    <p class="text-xl font-semibold text-gray-900">{{ $item->nama }}</p>
                                </a>
                            </div>
                            <div class="mt-6">
                                {{-- Harga --}}
                                <p class="text-lg font-bold text-gray-800">
                                    Rp {{ number_format($item->harga, 0, ',', '.') }}
                                    <span class="text-base font-normal text-gray-500">/ {{ $item->periode_harga }}</span>
                                </p>
                                {{-- Tombol Detail --}}
                                <a href="{{ route('penginapan.detail', $item->slug) }}"
                                    class="inline-block w-full px-4 py-2 mt-4 font-semibold text-center text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">
                                    Lihat Detail
                                </a>
                            </div>
                        </div>
                    </div>
                @empty
                    {{-- Pesan jika data tidak ditemukan --}}
                    <div class="p-6 text-center text-gray-500 bg-white rounded-lg shadow-md lg:col-span-3">
                        <p>Belum ada data penginapan yang tersedia saat ini.</p>
                    </div>
                @endforelse
            </div>

            {{-- Navigasi Paginasi --}}
            <div class="mt-8">
                {{ $all_penginapan->links() }}
            </div>

        </div>
    </div>
</x-guest-layout>