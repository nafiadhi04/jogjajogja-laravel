{{-- resources/views/auth/login.blade.php --}}
<x-guest-layout>
    <div class="flex items-center justify-center min-h-screen bg-gradient-to-br from-indigo-600 to-purple-600">
        <div class="w-full max-w-md p-8 bg-white shadow-xl rounded-2xl">
            <h1 class="mb-6 text-2xl font-bold text-center text-gray-800">Login Admin JogjaJogja</h1>

            <!-- Form Login -->
            <form method="POST" action="{{ route('login') }}">
                @csrf

                <!-- Email -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-semibold text-gray-700">Email</label>
                    <input type="email" name="email" value="{{ old('email') }}" required autofocus
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Password -->
                <div class="mb-4">
                    <label class="block mb-2 text-sm font-semibold text-gray-700">Password</label>
                    <input type="password" name="password" required
                        class="w-full px-3 py-2 border rounded-lg focus:outline-none focus:ring-2 focus:ring-indigo-500">
                </div>

                <!-- Remember Me -->
                <div class="flex items-center mb-4">
                    <input type="checkbox" name="remember" id="remember_me"
                        class="text-indigo-600 border-gray-300 rounded focus:ring-indigo-500">
                    <label for="remember_me" class="ml-2 text-sm text-gray-600">Ingat saya</label>
                </div>

                <!-- Tombol Login -->
                <div class="flex items-center justify-between">
                    <button type="submit"
                        class="w-full px-4 py-2 font-semibold text-white transition bg-indigo-600 rounded-lg hover:bg-indigo-700">
                        Masuk
                    </button>
                </div>
            </form>

            <!-- Link Lupa Password -->
            <div class="mt-4 text-center">
                <a href="{{ route('password.request') }}" class="text-sm text-indigo-600 hover:underline">
                    Lupa password?
                </a>
            </div>
        </div>
    </div>
</x-guest-layout>   