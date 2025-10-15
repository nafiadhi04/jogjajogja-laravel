<x-app-layout>
    {{-- Compact users index (UI dipadatkan, logika tetap sama) --}}
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
        }
    }">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 bg-white rounded-md shadow">

                <div class="flex items-center justify-between mb-3">
                    <h1 class="text-lg font-semibold text-gray-800">Daftar User</h1>
                    <a href="{{ route('admin.users.create') }}"
                        class="px-3 py-1 text-xs font-semibold text-white bg-indigo-600 rounded hover:bg-indigo-700">
                        Tambah User
                    </a>
                </div>

                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 table-fixed">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="w-16 px-2 py-2 text-left border">ID</th>
                                <th class="px-2 py-2 text-left border">Nama</th>
                                <th class="px-2 py-2 text-left border w-60">Email</th>
                                <th class="px-2 py-2 text-left border w-28">Role</th>
                                <th class="px-2 py-2 text-left border w-36">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($users as $user)
                                <tr class="odd:bg-white even:bg-gray-50">
                                    <td class="px-2 py-2 text-sm text-gray-700 border">{{ $user->id }}</td>
                                    <td class="px-2 py-2 text-sm font-medium text-gray-800 border">{{ $user->name }}</td>
                                    <td class="px-2 py-2 text-sm text-gray-700 truncate border">{{ $user->email }}</td>
                                    <td class="px-2 py-2 text-sm text-gray-700 border">
                                        <span
                                            class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full 
                                                {{ $user->role === 'admin' ? 'bg-indigo-100 text-indigo-800' : 'bg-gray-100 text-gray-800' }}">
                                            {{ ucfirst($user->role) }}
                                        </span>
                                    </td>
                                    <td class="px-2 py-2 border">
                                        <div class="flex items-center gap-2">
                                            <a href="{{ route('admin.users.edit', $user->id) }}"
                                                class="px-2 py-1 text-xs font-medium text-white bg-yellow-500 rounded hover:bg-yellow-600">
                                                Edit
                                            </a>

                                            <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                                class="inline"
                                                onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                                @csrf
                                                @method('DELETE')
                                                <button type="submit"
                                                    class="px-2 py-1 text-xs font-medium text-white bg-red-600 rounded hover:bg-red-700">
                                                    Hapus
                                                </button>
                                            </form>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>

                {{-- Jika $users adalah paginator, tampilkan links (pakai kelas compact) --}}
                @if(method_exists($users, 'links'))
                    <div class="mt-3">
                        {{ $users->links() }}
                    </div>
                @endif

            </div>
        </div>
    </div>
</x-app-layout>