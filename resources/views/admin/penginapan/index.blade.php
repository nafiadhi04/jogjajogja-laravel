<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white rounded-lg shadow-xl">

                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold text-gray-800">Kelola Artikel Penginapan</h1>
                    <a href="{{ route('admin.penginapan.create') }}" class="px-4 py-2 font-semibold text-white transition bg-indigo-600 rounded-md hover:bg-indigo-700">
                        + Tambah Artikel
                    </a>
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
                                <th class="px-4 py-3 text-left border">No.</th>
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
                                        {{ ($all_penginapan->currentPage() - 1) * $all_penginapan->perPage() + $loop->iteration }}
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->nama }}" class="object-cover w-24 h-16 rounded-md">
                                    </td>
                                    <td class="px-4 py-2 border">
                                        @if($item->status == 'diterima')
                                            <a href="{{ route('penginapan.detail', $item->slug) }}" target="_blank" class="font-semibold text-indigo-600 hover:underline">
                                                {{ $item->nama }}
                                            </a>
                                        @else
                                            <span class="font-semibold text-gray-800">{{ $item->nama }}</span>
                                        @endif
                                        @if($item->status == 'revisi' && $item->catatan_revisi)
                                            <div class="p-2 mt-2 text-xs text-red-800 bg-red-100 rounded-md">
                                                <strong>Catatan Revisi:</strong> {{ $item->catatan_revisi }}
                                            </div>
                                        @endif
                                    </td>
                                    @if(Auth::user()->role === 'admin')
                                        <td class="px-4 py-2 border">
                                            {{-- ========================================================== --}}
                                            {{-- PERUBAHAN UTAMA ADA DI SINI --}}
                                            {{-- ========================================================== --}}
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
                                            {{-- Aksi untuk Admin --}}
                                            @can('admin')
                                                @if(in_array($item->status, ['verifikasi', 'revisi']))
                                                    <button @click="modalOpen = true" class="px-3 py-1 text-sm text-white bg-blue-600 rounded-md hover:bg-blue-700">Verifikasi</button>
                                                @endif
                                                <a href="{{ route('admin.penginapan.edit', $item) }}" class="px-3 py-1 text-sm text-white bg-yellow-500 rounded-md hover:bg-yellow-600">Edit</a>
                                            @endcan

                                            {{-- Aksi untuk Member --}}
                                            @if(Auth::user()->role === 'member' && $item->status === 'revisi')
                                                <a href="{{ route('admin.penginapan.edit', $item) }}" class="px-3 py-1 text-sm text-white bg-yellow-500 rounded-md hover:bg-yellow-600">Revisi Artikel</a>
                                            @endif
                                            
                                            {{-- Tombol Hapus untuk semua (logika otorisasi ada di controller) --}}
                                            <form action="{{ route('admin.penginapan.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                                                @csrf @method('DELETE')
                                                <button type="submit" class="px-3 py-1 text-sm text-white bg-red-600 rounded-md hover:bg-red-700">Hapus</button>
                                            </form>
                                        </div>

                                        {{-- Modal untuk Update Status (hanya untuk admin) --}}
                                        @can('admin')
                                        <div x-show="modalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                            <div x-show="modalOpen" x-transition class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                                            <div x-show="modalOpen" x-transition @click.outside="modalOpen = false" class="relative w-full max-w-lg bg-white rounded-lg shadow-xl">
                                                <form action="{{ route('admin.penginapan.status.update', $item) }}" method="POST">
                                                    @csrf @method('PATCH')
                                                    <div class="p-6">
                                                        <h3 class="text-lg font-semibold text-left text-gray-900">Update Status untuk: {{ $item->nama }}</h3>
                                                        <div class="mt-4 space-y-4 text-left" x-data="{ status: 'diterima' }">
                                                            <div>
                                                                <label class="block text-sm font-medium text-gray-700">Status Baru</label>
                                                                <select name="status" x-model="status" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                                    <option value="diterima">Diterima</option>
                                                                    <option value="revisi">Revisi</option>
                                                                </select>
                                                            </div>
                                                            <div x-show="status === 'revisi'">
                                                                <label for="catatan_revisi" class="block text-sm font-medium text-gray-700">Catatan Revisi (Wajib diisi)</label>
                                                                <textarea name="catatan_revisi" rows="3" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end px-6 py-4 space-x-3 bg-gray-50">
                                                        <button type="button" @click="modalOpen = false" class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">Batal</button>
                                                        <button type="submit" class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">Simpan Status</button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                        @endcan
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    {{-- Colspan disesuaikan dengan jumlah kolom --}}
                                    <td colspan="{{ Auth::user()->role === 'admin' ? '7' : '6' }}" class="px-4 py-12 text-center text-gray-500 border">
                                        Tidak ada data artikel.
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

