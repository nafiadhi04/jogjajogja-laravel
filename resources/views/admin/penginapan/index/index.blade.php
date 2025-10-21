{{-- resources/views/admin/penginapan/index.blade.php --}}

<x-app-layout>
    {{-- route template & config --}}
    <div id="route-templates"
        data-penginapan-status-template="{{ url('admin/penginapan/__ID__/status') }}"
        data-penginapan-author-template="{{ url('admin/penginapan/__ID__/author') }}"
        class="hidden"></div>

    {{-- Parent x-data: table state (initialized via Alpine helper in @push scripts) --}}
    <div class="py-6" x-data="tableData({{ $all_penginapan->count() ?? 0 }})">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white rounded-md shadow-sm">

                {{-- Header (title + actions) --}}
                <div class="flex flex-col gap-3 mb-4 md:flex-row md:items-center md:justify-between">
                    <h1 class="text-lg font-semibold text-gray-800">Kelola Artikel Penginapan</h1>

                    <div class="flex items-center gap-3">
                        <div x-show="selectedIds.length > 0" x-transition>
                            <form action="{{ route('admin.penginapan.destroy.multiple') }}" method="POST" @submit.prevent="submitMassDelete($el)">
                                @csrf
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>
                                <button type="submit" class="px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                    Hapus (<span x-text="selectedIds.length"></span>)
                                </button>
                            </form>
                        </div>

                        <a href="{{ route('admin.penginapan.create') }}" class="px-3 py-1 text-xs font-semibold text-white bg-indigo-600 rounded hover:bg-indigo-700">
                            + Tambah Artikel
                        </a>
                    </div>
                </div>

                {{-- Search partial --}}
                @include('admin.penginapan.index._search')
                {{-- Alert untuk pesan sukses atau error --}}
                @if (session('success'))
                    <div class="p-4 mb-4 text-sm text-green-800 border border-green-300 rounded-lg bg-green-50" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                
                @if (session('error'))
                    <div class="p-4 mb-4 text-sm text-red-800 border border-red-300 rounded-lg bg-red-50" role="alert">
                        {{ session('error') }}
                    </div>
                @endif
                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 table-fixed">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="w-10 px-2 py-2 text-center border">
                                    <input type="checkbox" x-model="selectAll" @click="toggleSelectAll" class="rounded">
                                </th>
                                <th class="w-12 px-2 py-2 text-center border">No</th>
                                <th class="px-3 py-2 text-left border w-28">Thumbnail</th>
                                <th class="px-3 py-2 text-left border">Nama Artikel</th>
                                @if(Auth::user()->role === 'admin')
                                    <th class="px-3 py-2 text-left border w-44">Author</th>
                                @endif
                                <th class="w-20 px-3 py-2 text-center border">Views</th>
                                <th class="px-3 py-2 text-left border w-28">Status</th>
                                <th class="w-40 px-3 py-2 text-left border">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($all_penginapan as $item)
                                @include('admin.penginapan.index._row', ['item' => $item])
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->role === 'admin' ? '8' : '7' }}" class="px-4 py-12 text-center text-gray-500 border">
                                        Tidak ada data artikel yang cocok dengan pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $all_penginapan->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Include modals once (author + status) --}}
    @can('admin')
        @include('admin.penginapan.index._author_modal', ['authors' => $authors])
        @include('admin.penginapan.index._status_modal')
    @endcan

</x-app-layout>
