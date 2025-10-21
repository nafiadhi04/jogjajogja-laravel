{{-- resources/views/admin/users/edit/index.blade.php --}}
<x-app-layout>
    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="p-6 bg-white rounded-lg shadow">
                <h1 class="text-xl font-semibold text-gray-800">Edit User: {{ $user->name }}</h1>

                @if ($errors->any())
                    <div class="p-4 my-4 text-sm text-red-700 bg-red-100 rounded-lg" role="alert">
                        <div class="font-semibold">Oops! Terjadi kesalahan pada input Anda:</div>
                        <ul class="mt-2 ml-5 list-disc">
                            @foreach ($errors->all() as $error)
                                <li>{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                {{-- Form dengan Alpine untuk toggle password visibility --}}
                <form action="{{ route('admin.users.update', $user) }}" method="POST" class="mt-6 space-y-6"
                    x-data="{ showPassword: false, showConfirm: false }">
                    @csrf
                    @method('PUT')

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input type="text" name="name" id="name" value="{{ old('name', $user->name) }}" required
                            class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input type="email" name="email" id="email" value="{{ old('email', $user->email) }}" required
                            class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                    </div>

                    {{-- Role --}}
                    <div>
                        <label for="role" class="block text-sm font-medium text-gray-700">Role</label>
                        <select name="role" id="role" required
                            class="block w-full mt-1 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500">
                            <option value="admin" {{ old('role', $user->role) == 'admin' ? 'selected' : '' }}>Admin
                            </option>
                            <option value="member" {{ old('role', $user->role) == 'member' ? 'selected' : '' }}>Member
                            </option>
                            <option value="silver" {{ old('role', $user->role) == 'silver' ? 'selected' : '' }}>Silver
                            </option>
                            <option value="gold" {{ old('role', $user->role) == 'gold' ? 'selected' : '' }}>Gold</option>
                            <option value="platinum" {{ old('role', $user->role) == 'platinum' ? 'selected' : '' }}>
                                Platinum</option>
                        </select>
                    </div>

                    <hr class="border-t border-gray-200">

                    {{-- Password note --}}
                    <p class="text-sm text-gray-500">
                        Isi bagian password hanya bila ingin mengganti password. Biarkan kosong untuk mempertahankan
                        password saat ini.
                    </p>

                    {{-- Password baru (optional) --}}
                    <div class="relative">
                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <div class="relative mt-1">
                            <input :type="showPassword ? 'text' : 'password'" name="password" id="password"
                                class="block w-full pr-10 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Kosongkan bila tidak ingin mengubah password" autocomplete="new-password">
                            <button type="button" @click="showPassword = !showPassword" :aria-pressed="showPassword"
                                aria-label="Toggle password visibility"
                                class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 transition opacity-80 hover:opacity-100"
                                title="Tampilkan / sembunyikan password">
                                <svg x-show="!showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showPassword" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                    viewBox="0 0 24 24" stroke="currentColor" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.06 10.06 0 012.223-3.676" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Konfirmasi Password baru (optional) --}}
                    <div class="relative">
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                            Password Baru</label>
                        <div class="relative mt-1">
                            <input :type="showConfirm ? 'text' : 'password'" name="password_confirmation"
                                id="password_confirmation"
                                class="block w-full pr-10 text-sm border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500"
                                placeholder="Ulangi password baru (kosongkan bila tidak mengubah)"
                                autocomplete="new-password">
                            <button type="button" @click="showConfirm = !showConfirm" :aria-pressed="showConfirm"
                                aria-label="Toggle password confirmation visibility"
                                class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 transition opacity-80 hover:opacity-100"
                                title="Tampilkan / sembunyikan konfirmasi password">
                                <svg x-show="!showConfirm" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                    fill="none" viewBox="0 0 24 24" stroke="currentColor">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                </svg>
                                <svg x-show="showConfirm" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5"
                                    viewBox="0 0 24 24" stroke="currentColor" fill="none">
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M13.875 18.825A10.05 10.05 0 0112 19c-4.477 0-8.268-2.943-9.542-7a10.06 10.06 0 012.223-3.676" />
                                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                        d="M3 3l18 18" />
                                </svg>
                            </button>
                        </div>
                    </div>

                    {{-- Buttons --}}
                    <div class="flex justify-end gap-3 pt-4">
                        <a href="{{ route('admin.users.index') }}"
                            class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50">
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

    {{-- include the small scripts partial (keamanan & aksesibilitas keyboard handling) --}}
    @include('admin.users.edit._scripts')
</x-app-layout>