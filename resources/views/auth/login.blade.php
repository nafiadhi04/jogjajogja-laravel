{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    <div
        class="flex items-center justify-center min-h-screen px-4 py-12 bg-gradient-to-br from-gray-900 via-gray-800 to-gray-900">
        <div class="w-full max-w-md">
            {{-- Logo --}}
            <div class="flex flex-col items-center mb-8">
                <a href="/" class="inline-flex items-center">
                    <img src="{{ asset('images/logo-jogja-jogja.png') }}" alt="Logo JogjaJogja"
                        class="w-auto h-20 drop-shadow-lg">
                </a>
            
            </div>

            {{-- Card --}}
            <div class="overflow-hidden border border-gray-700 shadow-xl bg-white/6 backdrop-blur-md rounded-2xl">
                <div class="px-8 py-8">
                    <h2 class="text-2xl font-semibold text-center text-white">Login Akun</h2>
                    <p class="mt-2 text-sm text-center text-gray-400">Gunakan email dan password Anda</p>

                    {{-- Form Login --}}
                    <form method="POST" action="{{ route('login') }}" class="mt-6 space-y-5">
                        @csrf

                        {{-- Email --}}
                        <div>
                            <label for="email" class="block text-sm font-medium text-gray-200">Alamat Email</label>
                            <input id="email" type="email" name="email" value="{{ old('email') }}" required autofocus
                                class="block w-full px-3 py-2 mt-1 text-white placeholder-gray-400 bg-gray-800 border border-gray-700 rounded-lg shadow-sm focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                            @error('email')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Password --}}
                        <div>
                            <label for="password" class="block text-sm font-medium text-gray-200">Password</label>
                            <div class="relative">
                                <input id="password" type="password" name="password" required
                                    class="block w-full px-3 py-2 pr-10 mt-1 text-white placeholder-gray-400 bg-gray-800 border border-gray-700 rounded-lg shadow-sm peer focus:outline-none focus:ring-2 focus:ring-indigo-500 focus:border-indigo-500" />
                                {{-- toggle password --}}
                                <button type="button" data-target="password"
                                    class="absolute inset-y-0 right-0 flex items-center pr-3 text-gray-400 hover:text-gray-200 focus:outline-none"
                                    aria-label="Tampilkan password">
                                    <svg class="w-5 h-5" fill="none" stroke="currentColor" viewBox="0 0 24 24"
                                        xmlns="http://www.w3.org/2000/svg">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                            d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                </button>
                            </div>
                            @error('password')
                                <p class="mt-1 text-sm text-red-400">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Remember Me --}}
                        <div class="flex items-center justify-between">
                            <label class="flex items-center space-x-2 text-sm text-gray-300">
                                <input id="remember_me" type="checkbox" name="remember"
                                    class="text-indigo-500 bg-gray-800 border-gray-600 rounded focus:ring-indigo-500">
                                <span>Ingat saya</span>
                            </label>

                            <a href="{{ route('password.request') }}"
                                class="text-sm text-indigo-400 hover:text-indigo-300 hover:underline">
                                Lupa password?
                            </a>
                        </div>

                        {{-- Tombol Login --}}
                        <div>
                            <button type="submit"
                                class="inline-flex items-center justify-center w-full px-4 py-2 font-semibold text-white transition duration-200 bg-indigo-600 rounded-lg shadow-sm hover:bg-indigo-700 focus:outline-none focus:ring-2 focus:ring-indigo-400">
                                Masuk
                            </button>
                        </div>
                    </form>

                    {{-- Footer bawah --}}
                    <div class="mt-6 text-center">
                        <p class="text-sm text-gray-400">
                            Belum punya akun?
                            <a href="{{ route('register') }}"
                                class="text-indigo-400 hover:text-indigo-300 hover:underline">
                                Daftar Sekarang
                            </a>
                        </p>
                    </div>
                </div>
            </div>

        </div>
    </div>

    {{-- Script toggle password --}}
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