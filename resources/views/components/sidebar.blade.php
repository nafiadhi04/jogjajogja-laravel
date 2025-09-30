@props(['user'])

<aside @click.away="open = false" :class="open ? 'w-56' : 'w-16'"
    class="flex flex-col flex-shrink-0 text-sm text-gray-300 transition-all duration-300 ease-in-out shadow-lg bg-slate-800">

    <div class="flex items-center justify-center h-12 p-3 border-b border-slate-700">
        {{-- Logo besar --}}
        <a href="{{ route('dashboard') }}" x-show="open" x-transition class="absolute">
            <img src="{{ asset('images/logo-jogja-jogja.png') }}" alt="Logo JogjaJogja" class="h-6">
        </a>

        {{-- Logo kecil --}}
        <a href="{{ route('dashboard') }}" x-show="!open" x-transition>
            <img src="{{ asset('images/jogjajogja-min.png') }}" alt="Logo Mini JogjaJogja" class="h-6">
        </a>
    </div>

    {{-- Menu Navigasi --}}
    <nav class="flex-1 px-2 py-3 space-y-1">

        <a href="{{ route('dashboard') }}"
            class="flex items-center px-3 py-1.5 space-x-2 rounded-md text-xs transition-colors duration-200 hover:bg-slate-700 {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white border-l-2 border-teal-400' : 'text-gray-400 border-l-2 border-transparent' }}"
            title="Beranda">
            <span class="text-base material-symbols-outlined">
                home
            </span>
            <span x-show="open" x-transition class="whitespace-nowrap">Beranda</span>
        </a>

        @if($user && $user->role === 'admin')
            <a href="{{ route('admin.users.index') }}"
                class="flex items-center px-3 py-1.5 space-x-2 rounded-md text-xs transition-colors duration-200 hover:bg-slate-700 {{ request()->routeIs('admin.users.*') ? 'bg-slate-700 text-white border-l-2 border-teal-400' : 'text-gray-400 border-l-2 border-transparent' }}"
                title="Kelola User">
                <span class="text-base material-symbols-outlined">
                    manage_accounts
                </span>
                <span x-show="open" x-transition class="whitespace-nowrap">Kelola User</span>
            </a>
        @endif

        <a href="{{ route('admin.penginapan.index') }}"
            class="flex items-center px-3 py-1.5 space-x-2 rounded-md text-xs transition-colors duration-200 hover:bg-slate-700 {{ request()->routeIs(['admin.penginapan.index', 'admin.penginapan.create', 'admin.penginapan.edit']) ? 'bg-slate-700 text-white border-l-2 border-teal-400' : 'text-gray-400 border-l-2 border-transparent' }}"
            title="Kelola Artikel Penginapan">
            <span class="text-base material-symbols-outlined">
                villa
            </span>
            <span x-show="open" x-transition class="whitespace-nowrap">Kelola Penginapan</span>
        </a>

        <a href="{{ route('admin.wisata.index') }}"
            class="flex items-center px-3 py-1.5 space-x-2 rounded-md text-xs transition-colors duration-200 hover:bg-slate-700 {{ request()->routeIs('admin.wisata.*') ? 'bg-slate-700 text-white border-l-2 border-teal-400' : 'text-gray-400 border-l-2 border-transparent' }}"
            title="Kelola Artikel Wisata">
            <span class="text-base material-symbols-outlined">
                landscape
            </span>
            <span x-show="open" x-transition class="whitespace-nowrap">Kelola Wisata</span>
        </a>

    </nav>
</aside>