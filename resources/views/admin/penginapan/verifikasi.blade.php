<x-app-layout>
    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-6 overflow-hidden bg-white rounded-lg shadow-xl">

                <h1 class="mb-6 text-2xl font-bold text-gray-800">Verifikasi Artikel Penginapan</h1>

                {{-- Menampilkan pesan sukses setelah update status --}}
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
                                <th class="px-4 py-3 text-left border">Nama Artikel</th>
                                <th class="px-4 py-3 text-left border">Author</th>
                                <th class="px-4 py-3 text-left border">Status Saat Ini</th>
                                <th class="px-4 py-3 text-center border">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($penginapanUntukVerifikasi as $item)
                                {{-- Menggunakan Alpine.js untuk mengelola modal per baris --}}
                                <tr class="hover:bg-gray-50" x-data="{ modalOpen: false }">
                                    <td class="px-4 py-2 border">
                                        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->nama }}"
                                            class="object-cover w-24 h-16 rounded-md">
                                    </td>
                                    <td class="px-4 py-2 border">
                                        <a href="{{ route('penginapan.detail', $item->slug) }}" target="_blank"
                                            class="font-semibold text-indigo-600 hover:underline">
                                            {{ $item->nama }}
                                        </a>
                                    </td>
                                    <td class="px-4 py-2 border">{{ $item->author->name }}</td>
                                    <td class="px-4 py-2 border">
                                        <span
                                            class="px-2 py-1 text-xs font-semibold rounded-full 
                                                {{ $item->status == 'verifikasi' ? 'bg-yellow-100 text-yellow-800' : 'bg-red-100 text-red-800' }}">
                                            {{ ucfirst($item->status) }}
                                        </span>
                                    </td>
                                    <td class="px-4 py-2 text-center border">
                                        <button @click="modalOpen = true"
                                            class="px-3 py-1 text-sm font-medium text-white bg-blue-600 rounded-md hover:bg-blue-700">
                                            Ubah Status
                                        </button>

                                        {{-- Modal untuk Update Status --}}
                                        <div x-show="modalOpen" x-cloak
                                            class="fixed inset-0 z-50 flex items-center justify-center p-4">
                                            <div x-show="modalOpen" x-transition
                                                class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                                            <div x-show="modalOpen" x-transition @click.outside="modalOpen = false"
                                                class="relative w-full max-w-lg bg-white rounded-lg shadow-xl">
                                                <form action="{{ route('admin.penginapan.status.update', $item) }}"
                                                    method="POST">
                                                    @csrf
                                                    @method('PATCH')
                                                    <div class="p-6">
                                                        <h3 class="text-lg font-semibold text-left text-gray-900">Update
                                                            Status untuk: {{ $item->nama }}</h3>
                                                        <div class="mt-4 space-y-4 text-left">
                                                            <div>
                                                                <label
                                                                    class="block text-sm font-medium text-gray-700">Status
                                                                    Baru</label>
                                                                <select name="status" x-ref="statusSelect"
                                                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                                                                    <option value="diterima">Diterima</option>
                                                                    <option value="revisi">Revisi</option>
                                                                </select>
                                                            </div>
                                                            <div x-show="$refs.statusSelect.value === 'revisi'">
                                                                <label for="catatan_revisi"
                                                                    class="block text-sm font-medium text-gray-700">Catatan
                                                                    Revisi (Wajib diisi)</label>
                                                                <textarea name="catatan_revisi" rows="3"
                                                                    class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"></textarea>
                                                            </div>
                                                        </div>
                                                    </div>
                                                    <div class="flex justify-end px-6 py-4 space-x-3 bg-gray-50">
                                                        <button type="button" @click="modalOpen = false"
                                                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                                                            Batal
                                                        </button>
                                                        <button type="submit"
                                                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                                                            Simpan Status
                                                        </button>
                                                    </div>
                                                </form>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="5" class="px-4 py-12 text-center text-gray-500 border">
                                        Tidak ada artikel yang memerlukan verifikasi saat ini.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                <div class="mt-6">
                    {{ $penginapanUntukVerifikasi->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>