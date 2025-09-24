@props(['user'])

<aside :class="open ? 'w-64' : 'w-20'"
    class="flex flex-col flex-shrink-0 text-gray-300 transition-all duration-300 ease-in-out shadow-xl bg-slate-800">

    {{-- ========================================================== --}}
    {{-- PERBAIKAN UTAMA: Header dirombak untuk menampilkan logo dinamis --}}
    {{-- ========================================================== --}}
    <div class="flex items-center justify-center p-4 border-b h-14 border-slate-700">
        {{-- Logo besar (tampil saat sidebar terbuka) --}}
        <a href="{{ route('dashboard') }}" x-show="open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0" class="absolute">
            <img src="{{ asset('images/logo-jogja-jogja.png') }}" alt="Logo JogjaJogja" class="w-auto max-w-full h-7">
        </a>

        {{-- Logo kecil (tampil saat sidebar tertutup) --}}
        <a href="{{ route('dashboard') }}" x-show="!open" x-transition:enter="transition ease-out duration-200"
            x-transition:enter-start="opacity-0" x-transition:enter-end="opacity-100"
            x-transition:leave="transition ease-in duration-100" x-transition:leave-start="opacity-100"
            x-transition:leave-end="opacity-0">
            {{-- Pastikan Anda memiliki file logo-jogja-jogja-min.png di public/images --}}
            <img src="{{ asset('images/jogjajogja-min.png') }}" alt="Logo Mini JogjaJogja" class="w-auto h-7">
        </a>
    </div>



    {{-- Menu Navigasi --}}
    <nav class="flex-1 px-2 py-4 space-y-2">

        <a href="{{ route('dashboard') }}"
            class="flex items-center px-4 py-2 space-x-3 rounded-md transition-colors duration-200 hover:bg-slate-700 {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white border-l-4 border-teal-400' : 'text-gray-400 border-l-4 border-transparent' }}"
            title="Beranda">
            <span class="material-symbols-outlined">
                home
            </span>
            <span x-show="open" x-transition class="whitespace-nowrap">Beranda</span>
        </a>

        @if($user && $user->role === 'admin')
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center px-4 py-2 space-x-3 rounded-md transition-colors duration-200 hover:bg-slate-700 {{ request()->routeIs('admin.users.*') ? 'bg-slate-700 text-white border-l-4 border-teal-400' : 'text-gray-400 border-l-4 border-transparent' }}"
                title="Kelola User">
                <span class="material-symbols-outlined">
                    manage_accounts
                </span>
                <span x-show="open" x-transition class="whitespace-nowrap">Kelola User</span>
            </a>
        @endif

        <a href="{{ route('admin.penginapan.index') }}"
            class="flex items-center px-4 py-2 space-x-3 rounded-md transition-colors duration-200 hover:bg-slate-700 {{ request()->routeIs(['admin.penginapan.index', 'admin.penginapan.create', 'admin.penginapan.edit']) ? 'bg-slate-700 text-white border-l-4 border-teal-400' : 'text-gray-400 border-l-4 border-transparent' }}"
            title="Kelola Artikel Penginapan">
            <span class="material-symbols-outlined">
                villa
            </span>
            <span x-show="open" x-transition class="whitespace-nowrap">Kelola Penginapan</span>
        </a>

    </nav>
</aside>