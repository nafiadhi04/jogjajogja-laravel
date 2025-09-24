<x-app-layout>
    {{-- Menggunakan Alpine.js untuk mengelola state tabel --}}
    <div class="py-12" x-data="{
        selectedIds: [],
        selectAll: false,
        itemsOnPage: {{ $all_penginapan->count() }},
        toggleSelectAll() {
            this.selectAll = !this.selectAll;
            if (this.selectAll) {
                this.selectedIds = Array.from(document.querySelectorAll('.item-checkbox')).map(cb => cb.value);
            } else {
                this.selectedIds = [];
            }
        },
        updateSelectAllState() {
            this.selectAll = this.itemsOnPage > 0 && this.selectedIds.length === this.itemsOnPage;
        }
    }">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white rounded-lg shadow-xl">

                {{-- Header Halaman dengan Tombol Aksi --}}
                <div class="flex flex-col md:flex-row md:items-center md:justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Kelola Artikel Penginapan</h1>

                    <div class="flex items-center space-x-4 mt-4 md:mt-0">
                        <!-- Tombol Hapus Massal -->
                        <div x-show="selectedIds.length > 0" x-transition>
                            <form action="{{ route('admin.penginapan.destroy.multiple') }}" method="POST"
                                onsubmit="return confirm('Anda yakin ingin menghapus ' + selectedIds.length + ' artikel yang dipilih?');">
                                @csrf
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>
                                <button type="submit"
                                    class="px-4 py-2 font-semibold text-white bg-red-600 rounded-md hover:bg-red-700">
                                    Hapus (<span x-text="selectedIds.length"></span>)
                                </button>
                            </form>
                        </div>

                        {{-- Tombol Tambah Artikel --}}
                        <a href="{{ route('admin.penginapan.create') }}"
                            class="px-4 py-2 font-semibold text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">
                            + Tambah Artikel
                        </a>
                    </div>
                </div>

                <!-- Formulir Filter Pencarian -->
                <div class="p-4 mb-6 bg-gray-50 rounded-lg">
                    <form action="{{ route('admin.penginapan.index') }}" method="GET">
                        <div class="grid grid-cols-1 gap-4 md:grid-cols-4">
                            <div class="md:col-span-3">
                                <label for="search" class="text-sm font-medium text-gray-700">Cari Artikel</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                                    placeholder="Ketik nama penginapan, kota, tipe, status, atau author...">
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit"
                                    class="w-full px-4 py-2 font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700">Cari</button>
                                <a href="{{ route('admin.penginapan.index') }}"
                                    class="w-full px-4 py-2 font-semibold text-center text-gray-700 bg-gray-200 rounded-md hover:bg-gray-300">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                @if(session('success'))
                    <div class="px-4 py-3 mb-4 text-green-700 bg-green-100 border border-green-400 rounded" role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full border border-gray-200">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="px-4 py-3 border">
                                    <input type="checkbox" x-model="selectAll" @click="toggleSelectAll" class="rounded">
                                </th>
                                <th class="px-4 py-3 text-left border">Thumbnail</th>
                                <th class="px-4 py-3 text-left border">Nama Artikel</th>
                                @if(Auth::user()->role === 'admin')
                                    <th class="px-4 py-3 text-left border">Author</th>
                                @endif
                                <th class="px-4 py-3 text-left border">Views</th>
                                <th class="px-4 py-3 text-left border">Status</th>
                                <th class="px-4 py-3 text-left border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($all_penginapan as $item)
                                <tr class="hover:bg-gray-50" x-data="{ modalOpen: false }">
                                    <td class="px-4 py-2 text-center border">
                                        <input type="checkbox" value="{{ $item->id }}" x-model="selectedIds"
                                            @change="updateSelectAllState" class="rounded item-checkbox">
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->nama }}"
                                            class="object-cover w-24 h-16 rounded-md">
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <span class="font-semibold text-gray-800">{{ $item->nama }}</span>
                                        @if($item->status == 'revisi' && $item->catatan_revisi)
                                            {{-- ========================================================== --}}
                                            {{-- PERBAIKAN UTAMA: Menambahkan kelas untuk word wrap --}}
                                            {{-- ========================================================== --}}
                                            <div
                                                class="p-2 mt-2 text-xs text-red-800 bg-red-100 rounded-md break-words max-w-xs">
                                                <strong>Catatan Revisi:</strong> {{ $item->catatan_revisi }}
                                            </div>
                                        @endif
                                    </td>
                                    @if(Auth::user()->role === 'admin')
                                        <td class="px-4 py-2 border">
                                            <div>{{ $item->author->name }}</div>
                                            <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                        @if($item->author->role == 'admin') bg-indigo-100 text-indigo-800 
                                                        @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($item->author->role) }}
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-4 py-2 text-center border">
                                        {{ $item->views }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <span class="px-2 py-1 text-xs font-semibold rounded-full 
                                                @if($item->status == 'diterima') bg-green-100 text-green-800
                                                @elseif($item->status == 'verifikasi') bg-yellow-100 text-yellow-800
                                                @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <div class="flex items-center space-x-2">
                                            @can('admin')
                                                @if(in_array($item->status, ['verifikasi', 'revisi']))
                                                    <button @click="modalOpen = true"
                                                        class="px-3 py-1 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700">Verifikasi</button>
                                                @endif
                                            @endcan

                                            @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'member' && $item->status === 'revisi'))
                                                <a href="{{ route('admin.penginapan.edit', $item) }}"
                                                    class="px-3 py-1 text-sm text-white bg-yellow-500 rounded-md hover:bg-yellow-600">Edit</a>
                                            @endif

                                            <form action="{{ route('admin.penginapan.destroy', $item) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="px-3 py-1 text-sm text-white bg-red-600 rounded-md hover:bg-red-700">Hapus</button>
                                            </form>
                                        </div>

                                        @can('admin')
                                            <div x-show="modalOpen" x-cloak
                                                class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div x-show="modalOpen" x-transition
                                                    class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                                                <div x-show="modalOpen" x-transition @click.outside="modalOpen = false"
                                                    class="relative w-full max-w-lg bg-white rounded-lg shadow-xl">
                                                    <form action="{{ route('admin.penginapan.status.update', $item) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        <div class="p-6">
                                                            <h3 class="text-lg font-semibold text-left text-gray-900">Update
                                                                Status untuk: {{ $item->nama }}</h3>
                                                            <div class="mt-4 space-y-4 text-left"
                                                                x-data="{ status: 'diterima' }">
                                                                <div>
                                                                    <label
                                                                        class="block text-sm font-medium text-gray-700">Status
                                                                        Baru</label>
                                                                    <select name="status" x-model="status"
                                                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                                        <option value="diterima">Diterima</option>
                                                                        <option value="revisi">Revisi</option>
                                                                    </select>
                                                                </div>
                                                                <div x-show="status === 'revisi'">
                                                                    <label for="catatan_revisi"
                                                                        class="block text-sm font-medium text-gray-700">Catatan
                                                                        Revisi (Wajib diisi)</label>
                                                                    <textarea name="catatan_revisi" rows="3"
                                                                        class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="px-6 py-4 bg-gray-50 flex justify-end space-x-3">
                                                            <button type="button" @click="modalOpen = false"
                                                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Batal</button>
                                                            <button type="submit"
                                                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">Simpan
                                                                Status</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->role === 'admin' ? '8' : '7' }}"
                                        class="px-4 py-12 text-center text-gray-500 border">
                                        Tidak ada data artikel yang cocok dengan pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $all_penginapan->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>