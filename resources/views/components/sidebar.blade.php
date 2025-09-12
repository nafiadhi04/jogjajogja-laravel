@props(['user'])

{{--
Komponen sidebar sekarang hanya berisi tag <aside>.
    State 'open' dikontrol oleh layout induk (app.blade.php).
    Kelas 'flex-shrink-0' penting agar sidebar tidak ikut menyusut.
    --}}
    <aside :class="open ? 'w-64' : 'w-20'"
        class="flex flex-col flex-shrink-0 text-gray-200 transition-all duration-300 ease-in-out bg-gray-800 shadow-xl">

        {{-- Header Sidebar: Logo & Tombol Toggle --}}
        <div class="flex items-center justify-between h-16 px-6 border-b border-gray-700">
            <span x-show="open" x-transition class="text-xl font-bold text-white whitespace-nowrap">
                Dashboard
            </span>

            <button @click="open = !open"
                class="p-2 -mr-2 rounded-full hover:bg-gray-700 focus:outline-none focus:ring-2 focus:ring-white">
                <svg class="w-6 h-6 transition-transform duration-300" :class="{'rotate-180': open}" fill="none"
                    stroke="currentColor" viewBox="0 0 24 24">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M9 5l7 7-7 7" />
                </svg>
            </button>
        </div>

        {{-- Menu Navigasi --}}
        <nav class="flex-1 mt-4 space-y-2">
            {{-- Memeriksa apakah user ada dan rolenya adalah admin --}}
            @if($user && $user->role === 'admin')
                {{-- Menu untuk Admin --}}
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-6 py-3 space-x-4 hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}"
                    title="Beranda">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span x-show="open" class="whitespace-nowrap">Beranda</span>
                </a>
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-6 py-3 space-x-4 hover:bg-gray-700 {{ request()->routeIs('admin.users.*') ? 'bg-gray-700' : '' }}"
                    title="Kelola User">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M17 20h5v-2a3 3 0 00-5.356-1.857M17 20H7m10 0v-2c0-.653-.125-1.273-.356-1.857M7 20H2v-2a3 3 0 015.356-1.857M7 20v-2c0-.653.125-1.273.356-1.857m0 0a5.002 5.002 0 019.288 0M15 7a3 3 0 11-6 0 3 3 0 016 0zm6 3a2 2 0 11-4 0 2 2 0 014 0zM7 10a2 2 0 11-4 0 2 2 0 014 0z">
                        </path>
                    </svg>
                    <span x-show="open" class="whitespace-nowrap">Kelola User</span>
                </a>

                {{-- === BAGIAN BARU DIMULAI DI SINI === --}}
                <a href="{{ route('admin.penginapan.index') }}"
                    class="flex items-center px-6 py-3 space-x-4 hover:bg-gray-700 {{ request()->routeIs('admin.penginapan.*') ? 'bg-gray-700' : '' }}"
                    title="Kelola Artikel Penginapan">
                    <svg class="w-6 h-6" xmlns="http://www.w3.org/2000/svg" fill="none" viewBox="0 0 24 24"
                        stroke-width="1.5" stroke="currentColor">
                        <path stroke-linecap="round" stroke-linejoin="round"
                            d="M2.25 21h19.5m-18-18v18m10.5-18v18m6-13.5V21M6.75 6.75h.75m-.75 3h.75m-.75 3h.75m3-6h.75m-.75 3h.75m-.75 3h.75M9 21v-3.375c0-.621.504-1.125 1.125-1.125h3.75c.621 0 1.125.504 1.125 1.125V21" />
                    </svg>
                    <span x-show="open" class="whitespace-nowrap">Kelola Penginapan</span>
                </a>
                {{-- === BAGIAN BARU BERAKHIR DI SINI === --}}

            @else
                {{-- Menu untuk Member atau role lainnya --}}
                <a href="{{ route('dashboard') }}"
                    class="flex items-center px-6 py-3 space-x-4 hover:bg-gray-700 {{ request()->routeIs('dashboard') ? 'bg-gray-700' : '' }}"
                    title="Beranda">
                    <svg class="w-6 h-6" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                            d="M3 12l2-2m0 0l7-7 7 7M5 10v10a1 1 0 001 1h3m10-11l2 2m-2-2v10a1 1 0 01-1 1h-3m-6 0a1 1 0 001-1v-4a1 1 0 011-1h2a1 1 0 011 1v4a1 1 0 001 1m-6 0h6">
                        </path>
                    </svg>
                    <span x-show="open" class="whitespace-nowrap">Beranda</span>
                </a>
            @endif
        </nav>
    </aside>