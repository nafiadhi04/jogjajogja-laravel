<x-app-layout>
    <div class="py-12">
        <div class="max-w-4xl mx-auto sm:px-6 lg:px-8">
            <div class="p-8 bg-white rounded-lg shadow-xl">
                <h1 class="text-2xl font-bold text-gray-800">Edit User: {{ $user->name }}</h1>
                @if ($errors->any())
                    <div class="p-4 my-6 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                        <span class="font-bold">Oops! Terjadi kesalahan pada input Anda:</span>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="mt-6 space-y-6">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" id="name"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                            value="{{ old('name', $user->name) }}" required>
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                            value="{{ old('email', $user->email) }}" required>
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm"
                            required>
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                            </option>
                            <option value="member" {{ old('role', $user->role) == 'member' ? 'selected' : '' }}>Member
                            </option>
                        </select>
                    </div>

                    <hr>

                    {{-- Password (Opsional) --}}
                    <p class="text-sm text-gray-500">Isi bagian di bawah ini hanya jika Anda ingin mengubah password.
                    </p>
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input type="password" name="password" id="password"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>

                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                            Password Baru</label>
                        <input type="password" name="password_confirmation" id="password_confirmation"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm">
                    </div>

                    {{-- Tombol Aksi --}}
                    <div class="flex justify-end pt-4">
                        <a href="{{ route('admin.users.index') }}"
                            class="px-4 py-2 mr-3 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
                            Batal
                        </a>
                        <button type="submit"
                            class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700">
                            Simpan Perubahan
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>