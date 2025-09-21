<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white rounded-lg shadow-xl">

                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Daftar Artikel Penginapan</h1>
                    <a href="{{ route('admin.penginapan.create') }}"
                        class="px-4 py-2 font-semibold text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">
                        + Tambah Artikel
                    </a>
                </div>

                {{-- Menampilkan pesan sukses setelah operasi CRUD --}}
                @if(session('success'))
                    <div class="px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 text-left border">Thumbnail</th>
                                <th class="px-4 py-3 text-left border">Nama</th>
                                <th class="px-4 py-3 text-left border">Kota</th>
                                <th class="px-4 py-3 text-left border">Harga</th>
                                <th class="px-4 py-3 text-left border">Views</th>
                                <th class="px-4 py-3 text-left border">Author</th>
                                <th class="px-4 py-3 text-left border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($all_penginapan as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-4 py-2 border">
                                        {{-- ========================================================== --}}
                                        {{-- INI ADALAH CARA PEMANGGILAN GAMBAR YANG BENAR --}}
                                        {{-- ========================================================== --}}
                                        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->nama }}"
                                            class="object-cover w-24 h-16 rounded-md">
                                    </td>
                                    <td class="px-4 py-2 border">{{ $item->nama }}</td>
                                    <td class="px-4 py-2 border">{{ $item->kota }}</td>
                                    <td class="px-4 py-2 border">Rp {{ number_format($item->harga) }}</td>
                                    <td class="px-4 py-2 border">{{ $item->views }}</td>
                                    <td class="px-4 py-2 border">{{ $item->author->name }}</td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex items-center space-x-2">
                                            {{-- Tombol Edit (menggunakan slug) --}}
                                            <a href="{{ route('admin.penginapan.edit', $item) }}"
                                                class="px-3 py-1 text-sm font-medium text-white bg-yellow-500 rounded-md hover:bg-yellow-600">
                                                Edit
                                            </a>
                                            {{-- Tombol Hapus (menggunakan slug) --}}
                                            <form action="{{ route('admin.penginapan.destroy', $item) }}" method="POST"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus artikel ini?');">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 text-sm font-medium text-white bg-red-600 rounded-md hover:bg-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="7" class="px-4 py-12 text-center text-gray-500 border">
                                        Belum ada data artikel penginapan.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Navigasi Paginasi --}}
                <div class="mt-6">
                    {{ $all_penginapan->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>