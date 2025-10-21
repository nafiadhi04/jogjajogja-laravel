{{-- resources/views/admin/users/index/index.blade.php --}}
<x-app-layout>
    <div class="py-8" x-data="{
        selectedIds: [],
        selectAll: false,
        itemsOnPage: {{ $users->count() }},
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
            <div class="p-4 bg-white rounded-lg shadow-sm">
                {{-- Header --}}
                <div class="flex flex-col gap-3 mb-4 md:flex-row md:items-center md:justify-between">
                    <div>
                        <h1 class="text-lg font-semibold text-gray-800">Kelola User</h1>
                    </div>

                    <div class="flex items-center gap-3">
                        <div x-show="selectedIds.length > 0" x-transition class="flex items-center">
                            <form action="{{ route('admin.users.destroy.multiple') }}" method="POST"
                                onsubmit="return confirm('Anda yakin ingin menghapus ' + selectedIds.length + ' user yang dipilih?');">
                                @csrf
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>
                                <button type="submit"
                                    class="px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                    Hapus (<span x-text="selectedIds.length"></span>)
                                </button>
                            </form>
                        </div>

                        <a href="{{ route('admin.users.create') }}"
                            class="px-3 py-1 text-xs font-semibold text-white bg-indigo-600 rounded hover:bg-indigo-700">
                            + Tambah User
                        </a>
                    </div>
                </div>

                {{-- Search partial --}}
                @include('admin.users.index._search')

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="px-3 py-2 mb-3 text-sm text-green-700 bg-green-100 border border-green-200 rounded">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="px-3 py-2 mb-3 text-sm text-red-700 bg-red-100 border border-red-200 rounded">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 table-fixed">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="w-12 px-3 py-2 border">
                                    <input type="checkbox" x-model="selectAll" @click="toggleSelectAll" class="rounded">
                                </th>
                                <th class="w-12 px-3 py-2 text-left border">No.</th>
                                <th class="px-3 py-2 text-left border">Nama</th>
                                <th class="w-56 px-3 py-2 text-left border">Role</th>
                                <th class="px-3 py-2 text-left border w-36">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse($users as $index => $user)
                                @include('admin.users.index._row', ['user' => $user])
                            @empty
                                <tr>
                                    <td colspan="5" class="px-3 py-8 text-center text-gray-500 border">Tidak ada data user yang cocok.</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $users->links() }}
                </div>
            </div>
        </div>
    </div>
</x-app-layout>
