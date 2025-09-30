<x-app-layout>
    <div class="py-8" x-data="{
        selectedIds: [],
        selectAll: false,
        itemsOnPage: {{ $all_wisata->count() }},
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
            <div class="p-4 overflow-hidden bg-white rounded-lg shadow">

                <div class="flex flex-col mb-4 md:flex-row md:items-center md:justify-between">
                    <h1 class="text-lg font-semibold text-gray-800">Kelola Artikel Wisata</h1>
                    <a href="{{ route('admin.wisata.create') }}"
                        class="px-3 py-1 mt-3 text-xs font-semibold text-white bg-indigo-600 rounded hover:bg-indigo-700 md:mt-0">
                        + Tambah Artikel
                    </a>
                </div>

                <!-- Formulir Filter Pencarian -->
                <div class="p-3 mb-4 rounded bg-gray-50">
                    <form action="{{ route('admin.wisata.index') }}" method="GET">
                        <div class="grid grid-cols-1 gap-3 md:grid-cols-4">
                            <div class="md:col-span-3">
                                <label for="search" class="text-xs font-medium text-gray-700">Cari Artikel</label>
                                <input type="text" name="search" id="search" value="{{ request('search') }}"
                                    class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm"
                                    placeholder="Ketik nama wisata, kota, tipe, status, atau author...">
                            </div>
                            <div class="flex items-end space-x-2">
                                <button type="submit"
                                    class="w-full px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">Cari</button>
                                <a href="{{ route('admin.wisata.index') }}"
                                    class="w-full px-3 py-1 text-xs font-semibold text-center text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Reset</a>
                            </div>
                        </div>
                    </form>
                </div>

                @if(session('success'))
                    <div class="px-3 py-2 mb-3 text-sm text-green-700 bg-green-100 border border-green-200 rounded"
                        role="alert">
                        {{ session('success') }}
                    </div>
                @endif

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 table-fixed">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="w-10 px-2 py-2 border">
                                    <input type="checkbox" x-model="selectAll" @click="toggleSelectAll" class="rounded">
                                </th>
                                <th class="px-2 py-2 text-left border w-28">Thumbnail</th>
                                <th class="px-2 py-2 text-left border">Nama Artikel</th>
                                @if(Auth::user()->role === 'admin')
                                    <th class="px-2 py-2 text-left border w-36">Author</th>
                                @endif
                                <th class="w-20 px-2 py-2 text-center border">Views</th>
                                <th class="px-2 py-2 text-left border w-28">Status</th>
                                <th class="px-2 py-2 text-left border w-44">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($all_wisata as $item)
                                <tr class="align-top hover:bg-gray-50" x-data="{ modalOpen: false }">
                                    <td class="px-2 py-2 text-center align-middle border">
                                        <input type="checkbox" value="{{ $item->id }}" x-model="selectedIds"
                                            @change="updateSelectAllState" class="rounded item-checkbox">
                                    </td>
                                    <td class="px-2 py-2 border">
                                        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->nama }}"
                                            class="object-cover w-20 h-12 rounded-sm">
                                    </td>
                                    <td class="px-2 py-2 align-top border">
                                        @if($item->status == 'diterima')
                                            <a href="{{ route('wisata.detail', $item->slug) }}" target="_blank"
                                                class="text-sm font-medium text-indigo-600 hover:underline">
                                                {{ $item->nama }}
                                            </a>
                                        @else
                                            <div class="text-sm font-medium text-gray-800">{{ $item->nama }}</div>
                                        @endif

                                        @if($item->status == 'revisi' && $item->catatan_revisi)
                                            <div
                                                class="max-w-xs px-2 py-1 mt-2 text-xs text-red-800 break-words rounded bg-red-50">
                                                <strong>Catatan:</strong> {{ Str::limit($item->catatan_revisi, 120) }}
                                            </div>
                                        @endif
                                    </td>
                                    @if(Auth::user()->role === 'admin')
                                        <td class="px-2 py-2 align-top border">
                                            <div class="text-sm">{{ $item->author->name }}</div>
                                            <span class="inline-block px-2 py-0.5 mt-1 text-xs font-semibold rounded-full 
                                                                @if($item->author->role == 'admin') bg-indigo-100 text-indigo-800 
                                                                @else bg-gray-100 text-gray-800 @endif">
                                                {{ ucfirst($item->author->role) }}
                                            </span>
                                        </td>
                                    @endif
                                    <td class="px-2 py-2 text-sm text-center align-top border">
                                        {{ $item->views }}
                                    </td>
                                    <td class="px-2 py-2 align-top border">
                                        <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full 
                                                    @if($item->status == 'diterima') bg-green-100 text-green-800
                                                    @elseif($item->status == 'verifikasi') bg-yellow-100 text-yellow-800
                                                    @else bg-red-100 text-red-800 @endif">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 align-top border">
                                        <div class="flex flex-wrap items-center gap-2">
                                            @can('admin')
                                                @if(in_array($item->status, ['verifikasi', 'revisi']))
                                                    <button @click="modalOpen = true"
                                                        class="px-2 py-1 text-xs text-white bg-blue-600 rounded hover:bg-blue-700">Verifikasi</button>
                                                @endif
                                            @endcan

                                            @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'member' && $item->status === 'revisi'))
                                                <a href="{{ route('admin.wisata.edit', $item) }}"
                                                    class="px-2 py-1 text-xs font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600">Edit</a>
                                            @endif

                                            <form action="{{ route('admin.wisata.destroy', $item) }}" method="POST"
                                                onsubmit="return confirm('Yakin ingin menghapus artikel ini?');"
                                                class="inline">
                                                @csrf @method('DELETE')
                                                <button type="submit"
                                                    class="px-2 py-1 text-xs text-white bg-red-600 rounded hover:bg-red-700">Hapus</button>
                                            </form>
                                        </div>

                                        @can('admin')
                                            <div x-show="modalOpen" x-cloak
                                                class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                                <div x-show="modalOpen" x-transition
                                                    class="fixed inset-0 bg-gray-500 bg-opacity-60"></div>
                                                <div x-show="modalOpen" x-transition @click.outside="modalOpen = false"
                                                    class="relative w-full max-w-md bg-white rounded-lg shadow-lg">
                                                    <form action="{{ route('admin.wisata.status.update', $item) }}"
                                                        method="POST">
                                                        @csrf @method('PATCH')
                                                        <div class="p-4">
                                                            <h3 class="text-base font-semibold text-gray-900">Update Status:
                                                                {{ $item->nama }}</h3>
                                                            <div class="mt-3 space-y-3 text-left"
                                                                x-data="{ status: 'diterima' }">
                                                                <div>
                                                                    <label
                                                                        class="block text-sm font-medium text-gray-700">Status
                                                                        Baru</label>
                                                                    <select name="status" x-model="status"
                                                                        class="block w-full mt-1 text-sm border-gray-300 rounded">
                                                                        <option value="diterima">Diterima</option>
                                                                        <option value="revisi">Revisi</option>
                                                                    </select>
                                                                </div>
                                                                <div x-show="status === 'revisi'">
                                                                    <label for="catatan_revisi"
                                                                        class="block text-sm font-medium text-gray-700">Catatan
                                                                        Revisi (Wajib)</label>
                                                                    <textarea name="catatan_revisi" rows="3"
                                                                        class="block w-full mt-1 text-sm border-gray-300 rounded"></textarea>
                                                                </div>
                                                            </div>
                                                        </div>
                                                        <div class="flex justify-end px-4 py-3 space-x-2 bg-gray-50">
                                                            <button type="button" @click="modalOpen = false"
                                                                class="px-3 py-1 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                                                            <button type="submit"
                                                                class="px-3 py-1 text-sm font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700">Simpan</button>
                                                        </div>
                                                    </form>
                                                </div>
                                            </div>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->role === 'admin' ? '7' : '6' }}"
                                        class="px-4 py-8 text-sm text-center text-gray-500 border">
                                        Tidak ada data artikel wisata.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-4">
                    {{ $all_wisata->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>