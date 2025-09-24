<x-app-layout>
    {{-- PERBAIKAN: Mengganti '$all_penginapan' menjadi '$users' --}}
    <div class="py-12" x-data="{
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
            <div class="p-6 bg-white rounded-lg shadow-xl">

                <div class="flex items-center justify-between mb-6">
                    <h1 class="text-2xl font-bold">Daftar User</h1>
                    <a href="{{ route('admin.users.create') }}"
                        class="px-4 py-2 text-white bg-indigo-600 rounded-md hover:bg-indigo-700">
                        Tambah User
                    </a>
                </div>

                {{-- (Tambahkan tombol hapus massal jika diperlukan) --}}

                <table class="min-w-full border border-gray-200">
                    <thead>
                        <tr class="bg-gray-100">
                            {{-- (Tambahkan checkbox massal jika diperlukan) --}}
                            <th class="px-4 py-2 border">ID</th>
                            <th class="px-4 py-2 border">Nama</th>
                            <th class="px-4 py-2 border">Email</th>
                            <th class="px-4 py-2 border">Role</th>
                            <th class="px-4 py-2 border">Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach($users as $user)
                            <tr>
                                <td class="px-4 py-2 border">{{ $user->id }}</td>
                                <td class="px-4 py-2 border">{{ $user->name }}</td>
                                <td class="px-4 py-2 border">{{ $user->email }}</td>
                                <td class="px-4 py-2 border">{{ $user->role }}</td>
                                <td class="px-4 py-2 space-x-2 border">
                                    <a href="{{ route('admin.users.edit', $user->id) }}"
                                        class="text-blue-600 hover:underline">
                                        Edit
                                    </a>
                                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                                        class="inline"
                                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?')">
                                        @csrf
                                        @method('DELETE')
                                        <button type="submit" class="text-red-600 hover:underline">
                                            Hapus
                                        </button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>

            </div>
        </div>
    </div>
</x-app-layout>