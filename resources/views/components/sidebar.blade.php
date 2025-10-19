@props(['user'])

<aside :class="open ? 'w-64' : 'w-20'"
    class="flex flex-col flex-shrink-0 overflow-hidden text-gray-300 transition-all duration-500 ease-in-out shadow-xl bg-slate-800 transform-gpu"
    x-cloak style="will-change: width, transform, opacity;">

    {{-- Header Sidebar dengan Logo --}}
    <div class="flex items-center justify-center h-12 p-3 border-b border-slate-700">
        {{-- Logo besar (tampil saat sidebar terbuka) --}}
        <a href="{{ route('dashboard') }}" x-show="open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 -translate-y-1" x-transition:enter-end="opacity-100 translate-y-0"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 translate-y-0"
            x-transition:leave-end="opacity-0 translate-y-1" class="absolute">
            <img src="{{ asset('images/logo-jogja-jogja.png') }}" alt="Logo JogjaJogja" class="h-6">
        </a>

        {{-- Logo kecil (tampil saat sidebar tertutup) --}}
        <a href="{{ route('dashboard') }}" x-show="!open" x-transition:enter="transition ease-out duration-300"
            x-transition:enter-start="opacity-0 scale-90" x-transition:enter-end="opacity-100 scale-100"
            x-transition:leave="transition ease-in duration-200" x-transition:leave-start="opacity-100 scale-100"
            x-transition:leave-end="opacity-0 scale-95">
            <img src="{{ asset('images/jogjajogja-min.png') }}" alt="Logo Mini JogjaJogja" class="h-6">
        </a>
    </div>

    {{-- Profil Pengguna: clickable ke halaman profil (pakai route profile.edit yang ada di web.php) --}}
    <div class="border-b border-slate-700">
        <a href="{{ route('profile.edit') }}"
            class="flex items-center gap-3 p-2 no-underline h-14 group hover:no-underline focus:outline-none focus:ring-0">
            {{-- Avatar (selalu tampil) --}}
            <div class="flex-shrink-0 ml-3 mr-0">
                <img class="object-cover transition-transform duration-200 ease-out rounded-full w-9 h-9 ring-1 ring-slate-500 transform-gpu group-hover:scale-105"
                    :class="open ? 'translate-x-0 scale-100' : 'translate-x-0 scale-95'"
                    src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=FFFFFF&background=4F46E5' }}"
                    alt="User profile picture">
            </div>

            {{-- Nama & role (hanya saat open) --}}
            <div class="overflow-hidden" x-show="open" x-transition:enter="transition ease-out duration-350"
                x-transition:enter-start="opacity-0 -translate-x-2" x-transition:enter-end="opacity-100 translate-x-0"
                x-transition:leave="transition ease-in duration-200"
                x-transition:leave-start="opacity-100 translate-x-0" x-transition:leave-end="opacity-0 translate-x-2">
                <div class="flex items-baseline space-x-2">
                    {{-- Nama: ubah warna saat hover, tanpa underline --}}
                    <p class="text-sm font-semibold text-white truncate transition-colors duration-200 group-hover:text-indigo-400"
                        title="{{ $user->name }}">
                        {{ $user->name }}
                    </p>

                    {{-- Role badge --}}
                    <span
                        class="flex-shrink-0 inline-block px-1.5 py-0.5 text-[10px] font-semibold rounded-md transition-colors duration-200
                               {{ $user->role === 'admin' ? 'bg-indigo-200 text-indigo-800' : ($user->role === 'pending' ? 'bg-yellow-100 text-yellow-800' : 'bg-gray-200 text-gray-800') }}">
                        {{ ucfirst($user->role === 'pending' ? 'Pending' : $user->role) }}
                    </span>
                </div>
            </div>
        </a>
    </div>

    {{-- Jika user masih pending tunjukkan banner singkat --}}
    @if($user && $user->role === 'pending')
        <div class="px-3 py-2 text-xs text-yellow-800 border-b border-yellow-100 bg-yellow-50">
            <div class="flex items-center space-x-2">
                <svg class="w-4 h-4" viewBox="0 0 24 24" fill="none" stroke="currentColor">
                    <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                        d="M12 8v4m0 4h.01M21 12A9 9 0 1112 3a9 9 0 019 9z" />
                </svg>
                <div>
                    <div class="font-semibold">Akun Anda menunggu verifikasi</div>
                    <div class="text-[11px] text-yellow-700">Admin harus mengaktifkan akun untuk mengakses fitur ini.</div>
                </div>
            </div>
        </div>
    @endif

    {{-- Menu Navigasi --}}
    <nav class="flex-1 px-2 py-3 space-y-1">
        {{-- Beranda selalu tampil --}}
        <a href="{{ route('dashboard') }}"
            class="flex items-center px-5 py-1.5 space-x-2 rounded-md text-xs transition-colors duration-200 hover:bg-slate-700 {{ request()->routeIs('dashboard') ? 'bg-slate-700 text-white border-l-2 border-teal-400' : 'text-gray-400 border-l-2 border-transparent' }}"
            title="Beranda">
            <span class="text-base material-symbols-outlined">home</span>
            <span x-show="open" x-transition class="whitespace-nowrap">Beranda</span>
        </a>

        {{-- Tampilkan menu admin/member hanya jika role bukan pending --}}
        @if($user && $user->role !== 'pending')
            @if($user && $user->role === 'admin')
                <a href="{{ route('admin.users.index') }}"
                    class="flex items-center px-5 py-1.5 space-x-2 rounded-md text-xs transition-colors duration-200 hover:bg-slate-700 {{ request()->routeIs('admin.users.*') ? 'bg-slate-700 text-white border-l-2 border-teal-400' : 'text-gray-400 border-l-2 border-transparent' }}"
                    title="Kelola User">
                    <span class="text-base material-symbols-outlined">manage_accounts</span>
                    <span x-show="open" x-transition class="whitespace-nowrap">Kelola User</span>
                </a>
            @endif

            <a href="{{ route('admin.penginapan.index') }}"
                class="flex items-center px-5 py-1.5 space-x-2 rounded-md text-xs transition-colors duration-200 hover:bg-slate-700 {{ request()->routeIs(['admin.penginapan.index', 'admin.penginapan.create', 'admin.penginapan.edit']) ? 'bg-slate-700 text-white border-l-2 border-teal-400' : 'text-gray-400 border-l-2 border-transparent' }}"
                title="Kelola Artikel Penginapan">
                <span class="text-base material-symbols-outlined">villa</span>
                <span x-show="open" x-transition class="whitespace-nowrap">Kelola Penginapan</span>
            </a>

            <a href="{{ route('admin.wisata.index') }}"
                class="flex items-center px-5 py-1.5 space-x-2 rounded-md text-xs transition-colors duration-200 hover:bg-slate-700 {{ request()->routeIs('admin.wisata.*') ? 'bg-slate-700 text-white border-l-2 border-teal-400' : 'text-gray-400 border-l-2 border-transparent' }}"
                title="Kelola Artikel Wisata">
                <span class="text-base material-symbols-outlined">landscape</span>
                <span x-show="open" x-transition class="whitespace-nowrap">Kelola Wisata</span>
            </a>
        @endif
    </nav>
</aside>