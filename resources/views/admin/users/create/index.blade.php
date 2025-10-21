{{-- resources/views/admin/users/create.blade.php --}}
<x-app-layout>
    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Manajemen User') }}
        </h2>
    </x-slot>

    <div class="py-12">
        <div class="max-w-3xl mx-auto sm:px-6 lg:px-8">
            <div class="overflow-hidden bg-white shadow-sm sm:rounded-lg">
                <div class="p-6 md:p-8">

                    <h2 class="text-lg font-semibold text-gray-800">Form Tambah User Baru</h2>
                    <p class="mt-1 text-sm text-gray-600">
                        Isi detail di bawah ini untuk membuat akun baru.
                    </p>

                    {{-- Alerts --}}
                    @if(session('success'))
                        <div class="p-3 mt-4 text-sm text-green-700 bg-green-100 border border-green-200 rounded"
                            role="alert">
                            {{ session('success') }}
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="p-3 mt-4 text-sm text-red-700 bg-red-100 border border-red-200 rounded" role="alert">
                            {{ session('error') }}
                        </div>
                    @endif

                    {{-- Validation errors --}}
                    @if ($errors->any())
                        <div class="p-4 mt-4 text-sm text-red-700 bg-red-100 border-l-4 border-red-500 rounded"
                            role="alert">
                            <p class="font-semibold">Oops! Terjadi kesalahan:</p>
                            <ul class="mt-2 list-disc list-inside">
                                @foreach ($errors->all() as $error)
                                    <li class="text-xs">{{ $error }}</li>
                                @endforeach
                            </ul>
                        </div>
                    @endif

                    <div class="mt-6">
                        <form action="{{ route('admin.users.store') }}" method="POST" class="space-y-6">
                            @csrf

                            <div>
                                <label for="name" class="block mb-1 text-sm font-medium text-gray-700">Nama</label>
                                <input type="text" id="name" name="name" value="{{ old('name') }}"
                                    class="w-full px-3 py-2 text-gray-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>

                            <div>
                                <label for="email" class="block mb-1 text-sm font-medium text-gray-700">Email</label>
                                <input type="email" id="email" name="email" value="{{ old('email') }}"
                                    class="w-full px-3 py-2 text-gray-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                    required>
                            </div>

                            <div>
                                <label for="role" class="block mb-1 text-sm font-medium text-gray-700">Role</label>
                                <select id="role" name="role"
                                    class="w-full px-3 py-2 text-gray-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500">
                                    <option value="member" @selected(old('role') == 'member')>Member</option>
                                    <option value="admin" @selected(old('role') == 'admin')>Admin</option>
                                    <option value="silver" @selected(old('role') == 'admin')>Silver</option>
                                    <option value="gold" @selected(old('role') == 'admin')>Gold</option>
                                    <option value="platinum" @selected(old('role') == 'admin')>Platinum</option>
                                </select>
                            </div>

                            {{-- Password group with "intip" control --}}
                            <div>
                                <label for="password"
                                    class="block mb-1 text-sm font-medium text-gray-700">Password</label>
                                <div class="relative">
                                    <input type="password" id="password" name="password" autocomplete="new-password"
                                        class="w-full px-3 py-2 pr-10 text-gray-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                    <button type="button" id="togglePassword" aria-pressed="false"
                                        class="absolute inset-y-0 right-0 flex items-center px-2 text-gray-500 rounded-r-md hover:text-gray-700"
                                        title="Tampilkan / Sembunyikan password">
                                        <svg id="iconEye" xmlns="http://www.w3.org/2000/svg" class="w-5 h-5" fill="none"
                                            viewBox="0 0 24 24" stroke="currentColor" stroke-width="1.5"
                                            aria-hidden="true">
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                            <path stroke-linecap="round" stroke-linejoin="round"
                                                d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                        </svg>
                                    </button>
                                </div>
                            </div>

                            <div>
                                <label for="password_confirmation"
                                    class="block mb-1 text-sm font-medium text-gray-700">Konfirmasi Password</label>
                                <div class="relative">
                                    <input type="password" id="password_confirmation" name="password_confirmation"
                                        autocomplete="new-password"
                                        class="w-full px-3 py-2 pr-10 text-gray-900 border border-gray-300 rounded-md focus:outline-none focus:ring-2 focus:ring-blue-500"
                                        required>
                                    {{-- reuse same toggle control visually for pair (no duplicate button needed) --}}
                                </div>
                            </div>

                            <div class="flex items-center justify-between gap-3">
                                <div class="text-xs text-gray-500">
                                    <strong>Tip:</strong> Gunakan password yang kuat (minimal 8 karakter).
                                </div>

                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.users.index') }}"
                                        class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">
                                        Batal
                                    </a>

                                    <button type="submit"
                                        class="px-6 py-2 text-sm font-semibold text-white bg-blue-600 rounded-md hover:bg-blue-700 focus:outline-none focus:ring-2 focus:ring-blue-500">
                                        Simpan User
                                    </button>
                                </div>
                            </div>
                        </form>
                    </div>

                </div>
            </div>
        </div>
    </div>

    {{-- Toggle script: tempatkan di stack script layout --}}
    @push('scripts')
        <script>
            (function () {
                const toggleBtn = document.getElementById('togglePassword');
                if (!toggleBtn) return;

                const pwd = document.getElementById('password');
                const pwdConfirm = document.getElementById('password_confirmation');
                const iconEye = document.getElementById('iconEye');

                function setIcon(show) {
                    // simple swap: eye / eye-off inline SVG path change
                    if (!iconEye) return;
                    iconEye.innerHTML = show
                        ? `<path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.88 9.88a3 3 0 104.24 4.24"/>`
                        : `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
                }

                toggleBtn.addEventListener('click', function (e) {
                    const isHidden = (pwd.type === 'password');
                    pwd.type = isHidden ? 'text' : 'password';
                    pwdConfirm.type = isHidden ? 'text' : 'password';
                    toggleBtn.setAttribute('aria-pressed', String(isHidden));
                    setIcon(isHidden);
                });

                // accessibility: toggle with Enter/Space when focused
                toggleBtn.addEventListener('keydown', function (e) {
                    if (e.key === 'Enter' || e.key === ' ') {
                        e.preventDefault();
                        toggleBtn.click();
                    }
                });
            })();
        </script>
    @endpush
</x-app-layout>