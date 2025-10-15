<x-guest-layout>
    {{-- Register — dark themed modern card --}}
    <div
        class="flex items-center justify-center min-h-screen px-4 py-12 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
        <div class="w-full max-w-md">
            {{-- Brand / Logo --}}
            <div class="flex flex-col items-center mb-8">
                <a href="/" class="inline-flex items-center">
                    <img src="{{ asset('images/logo-jogja-jogja.png') }}" alt="Logo JogjaJogja"
                        class="w-auto h-20 drop-shadow-lg">
                </a>
            </div>

            {{-- Card --}}
            <div class="overflow-hidden border border-gray-700 shadow-xl bg-white/6 backdrop-blur-md rounded-2xl">
                <div class="px-8 py-8">
                    <h2 class="text-2xl font-semibold text-center text-white">Buat Akun Baru</h2>
                    <p class="mt-2 text-sm text-center text-gray-400">Daftar untuk mendapatkan akses penuh ke fitur</p>

                    <form method="POST" action="{{ route('register') }}" class="mt-6 space-y-5">
                        @csrf

                        {{-- Name --}}
                        <div>
                            <label for="name" class="block text-sm font-medium text-gray-200">Nama Lengkap</label>
                            <div class="mt-1">
                                <input id="name" name="name" type="text" value="{{ old('name') }}" required autofocus
                                    autocomplete="name"
                                    class="block w-full px-3 py-2 text-white placeholder-gray-400 bg-gray-800 border border-gray-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <x-input-error :messages="$errors->get('name')" class="mt-1 text-sm text-red-400" />
                        </div>

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-200">Alamat Email</label>
                            <div class="mt-1">
                                <input id="email" name="email" type="email" value="{{ old('email') }}" required
                                    autocomplete="username"
                                    class="block w-full px-3 py-2 text-white placeholder-gray-400 bg-gray-800 border border-gray-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            </div>
                            <x-input-error :messages="$errors->get('email')" class="mt-1 text-sm text-red-400" />
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-200">Password</label>
                            <div class="relative mt-1">
                                <input id="password" name="password" type="password" required
                                    autocomplete="new-password"
                                    class="block w-full px-3 py-2 pr-10 text-white placeholder-gray-400 bg-gray-800 border border-gray-700 rounded-lg shadow-sm peer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                {{-- toggle button --}}
                                <button type="button" data-target="password"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-200 focus:outline-none"
                                    aria-label="Tampilkan password">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <p class="mt-2 text-xs text-gray-400">Minimal 8 karakter. Gunakan kombinasi huruf dan angka.
                            </p>
                            <x-input-error :messages="$errors->get('password')" class="mt-1 text-sm text-red-400" />
                        </div>

                        {{-- Confirm Password --}}
                        <div>
                            <label for="password_confirmation"
                                class="block text-sm font-medium text-gray-200">Konfirmasi Password</label>
                            <div class="relative mt-1">
                                <input id="password_confirmation" name="password_confirmation" type="password" required
                                    autocomplete="new-password"
                                    class="block w-full px-3 py-2 pr-10 text-white placeholder-gray-400 bg-gray-800 border border-gray-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                <button type="button" data-target="password_confirmation"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-200 focus:outline-none"
                                    aria-label="Tampilkan konfirmasi password">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"></path>
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z">
                                        </path>
                                    </svg>
                                </button>
                            </div>
                            <x-input-error :messages="$errors->get('password_confirmation')"
                                class="mt-1 text-sm text-red-400" />
                        </div>

                        {{-- Actions --}}
                        <div class="flex items-center justify-between gap-3">
                            <a href="{{ route('login') }}"
                                class="text-sm text-gray-400 underline hover:text-indigo-300">Sudah punya akun?</a>

                            <button type="submit"
                                class="inline-flex items-center justify-center px-4 py-2 font-semibold text-white bg-indigo-600 rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                Daftar
                            </button>
                        </div>
                    </form>
                </div>

                {{-- Footer note --}}
                <div class="px-8 pb-6">
                </div>
            </div>

            {{-- Small footer --}}
        
        </div>
    </div>

    {{-- Small JS: toggle show/hide password --}}
    <script>
        document.addEventListener('click', function (e) {
            const btn = e.target.closest('button[data-target]');
            if (!btn) return;
            const targetId = btn.getAttribute('data-target');
            const input = document.getElementById(targetId);
            if (!input) return;

            if (input.type === 'password') {
                input.type = 'text';
                btn.setAttribute('aria-pressed', 'true');
            } else {
                input.type = 'password';
                btn.setAttribute('aria-pressed', 'false');
            }
        });
    </script>
</x-guest-layout>