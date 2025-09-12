<x-app-layout>
    <div class="py-12">
        <div class="max-w-lg p-6 mx-auto bg-white rounded-lg shadow-lg">

            <h2 class="mb-4 text-xl font-bold">Edit User</h2>

            <form action="{{ route('admin.users.update', $user) }}" method="POST">
                @csrf
                @method('PUT')

                <div class="mb-4">
                    <label class="block mb-1">Nama</label>
                    <input type="text" name="name" class="w-full px-3 py-2 border rounded"
                        value="{{ old('name', $user->name) }}">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Email</label>
                    <input type="email" name="email" class="w-full px-3 py-2 border rounded"
                        value="{{ old('email', $user->email) }}">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Role</label>
                    <select name="role" class="w-full px-3 py-2 border rounded">
                        <option value="member" {{ $user->role === 'member' ? 'selected' : '' }}>Member</option>
                        <option value="admin" {{ $user->role === 'admin' ? 'selected' : '' }}>Admin</option>
                    </select>
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Password (kosongkan jika tidak ingin diubah)</label>
                    <input type="password" name="password" class="w-full px-3 py-2 border rounded">
                </div>

                <div class="mb-4">
                    <label class="block mb-1">Konfirmasi Password</label>
                    <input type="password" name="password_confirmation" class="w-full px-3 py-2 border rounded">
                </div>

                <button type="submit" class="px-4 py-2 text-white bg-green-500 rounded">Update</button>
            </form>

        </div>
    </div>
</x-app-layout>