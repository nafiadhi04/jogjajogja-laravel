<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800 dark:text-gray-200">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">

                    {{-- [MODIFIKASI] Warna font diubah menjadi lebih gelap --}}
                    <h2 class="text-xl font-semibold text-gray-800">Form Tambah User Baru</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Isi detail di bawah ini untuk membuat akun baru.
                    </p>

                    <div class="mt-6">
                        @if ($errors->any())
                            <div class="p-4 mb-4 text-red-700 bg-red-100 border-l-4 border-red-500" role="alert">
                                <p class="font-bold">Oops! Terjadi kesalahan:</p>
                                <ul class="mt-2">
                                    @foreach ($errors->all() as $error)
                                        <li class="ml-4 list-disc">{{ $error }}</li>
                                    @endforeach
                                </ul>
                            </div>
                        @endif

                        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                {{-- [MODIFIKASI] Warna font label diubah menjadi lebih gelap --}}
                                <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Nama</label>
                                {{-- [MODIFIKASI] Class dark mode dihapus dari input --}}
                                <input type="text" id="name" name="name"
                                    class="w-full px-3 py-2 text-gray-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ old('name') }}" required>
                            </div>

                            <div>
                                <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email"
                                    class="w-full px-3 py-2 text-gray-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    value="{{ old('email') }}" required>
                            </div>

                            <div>
                                <label for="role" class="block mb-1 text-sm font-medium text-gray-700">Role</label>
                                <select id="role" name="role"
                                    class="w-full px-3 py-2 text-gray-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="member">Member</option>
                                    <option value="admin">Admin</option>
                                </select>
                            </div>

                            <div>
                                <label for="password"
                                    class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                                <input type="password" id="password" name="password"
                                    class="w-full px-3 py-2 text-gray-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="block mb-1 text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <input type="password" id="password_confirmation" name="password_confirmation"
                                    class="w-full px-3 py-2 text-gray-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>

                            <div class="flex justify-end mt-6">
                                <button type="submit"
                                    class="px-6 py-2 font-bold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
                                    Simpan User
                                </button>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>
</x-app-layout>