{{-- resources/views/admin/wisata/index/index.blade.php --}}
<x-app-layout>
    {{-- Alpine helper untuk table state --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tableData', (itemsOnPage = 0) => ({
                selectedIds: [],
                selectAll: false,
                itemsOnPage: Number(itemsOnPage) || 0,
                toggleSelectAll() {
                    this.selectAll = !this.selectAll;
                    if (this.selectAll) {
                        this.selectedIds = Array.from(document.querySelectorAll('.item-checkbox')).map(cb => parseInt(cb.value));
                    } else {
                        this.selectedIds = [];
                    }
                },
                updateSelectAllState() {
                    this.selectedIds = Array.from(new Set(this.selectedIds.map(i => parseInt(i))));
                    this.selectAll = (this.itemsOnPage > 0) && (this.selectedIds.length === this.itemsOnPage);
                },
                submitMassDelete(formEl) {
                    const count = this.selectedIds.length;
                    if (count === 0) return;
                    if (confirm('Anda yakin ingin menghapus ' + count + ' artikel wisata yang dipilih?')) {
                        formEl && formEl.submit();
                    }
                }
            }));
        });
    </script>

    <div class="py-6" x-data="tableData({{ $all_wisata->count() ?? 0 }})">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Route templates untuk membangun URL --}}
            <div id="route-templates"
                 data-wisata-status-template="{{ url('admin/wisata/__ID__/status') }}"
                 data-wisata-author-template="{{ url('admin/wisata/__ID__/author') }}"
                 class="hidden"></div>

            <div class="p-4 bg-white rounded-md shadow-sm">

                {{-- Header --}}
                <div class="flex flex-col gap-3 mb-4 md:flex-row md:items-center md:justify-between">
                    <h1 class="text-lg font-semibold text-gray-800">Kelola Artikel Wisata</h1>

                    <div class="flex items-center gap-3">
                        <div x-show="selectedIds.length > 0" x-transition>
                            <form action="{{ route('admin.wisata.destroy.multiple') }}" method="POST" @submit.prevent="submitMassDelete($el)">
                                @csrf
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>
                                <button type="submit" class="px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                    Hapus (<span x-text="selectedIds.length"></span>)
                                </button>
                            </form>
                        </div>

                        <a href="{{ route('admin.wisata.create') }}" class="px-3 py-1 text-xs font-semibold text-white bg-indigo-600 rounded hover:bg-indigo-700">
                            + Tambah Artikel
                        </a>
                    </div>
                </div>

                {{-- Search partial (consisten dengan penginapan) --}}
                @include('admin.wisata.index._search')

                {{-- Alerts (di atas tabel) --}}
                @if(session('success'))
                    <div class="px-3 py-2 mb-3 text-sm text-green-700 bg-green-100 border border-green-200 rounded" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="px-3 py-2 mb-3 text-sm text-red-700 bg-red-100 border border-red-200 rounded" role="alert">
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
                            @forelse ($all_wisata as $item)
                                @include('admin.wisata.index._row', ['item' => $item, 'loop' => $loop, 'all_wisata' => $all_wisata])
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
                    {{ $all_wisata->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Modals --}}
    @include('admin.wisata.index._author_modal')
    @include('admin.wisata.index._status_modal')
</x-app-layout>
