<x-guest-layout>

    {{-- Definisi Variabel Lokal untuk Breadcrumb (Mengatasi Error: Undefined variable $berandaRoute) --}}
    @php
        // Pastikan route('beranda') sudah didefinisikan di routes/web.php
        $berandaRoute = route('beranda');
        $penginapanListRoute = route('penginapan.list');
    @endphp

    {{-- Hero Section with Background Image --}}
    <div class="relative min-h-[60vh] sm:min-h-[50vh] md:min-h-[60vh] bg-center bg-no-repeat bg-cover"
        {{-- MODIFIKASI: Gradient dari hitam pekat (0.7) di atas, ke transparan (0.0) di 70% tinggi elemen. --}}
        style="background-image: linear-gradient(to bottom, rgba(0, 0, 0, 0.7) 0%, rgba(0, 0, 0, 0.0) 70%), url('https://cdn-image.hipwee.com/wp-content/uploads/2020/07/hipwee-jenispenginapan5-768x512.jpg');">
        
        {{-- Konten Hero --}}
        <div class="relative flex flex-col items-center justify-end h-full px-4 py-8 lg:py-12">
            
            <div class="flex flex-col items-center pt-16"> 
                {{-- Main Title --}}
                <h1 class="mb-3 text-2xl font-bold text-center text-white md:mb-4 md:text-4xl lg:text-5xl drop-shadow-lg">
                    Rekomendasi Penginapan
                </h1>

                {{-- Breadcrumb (PERBAIKAN UTAMA DI SINI) --}}
                <div class="mb-6 md:mb-6">
                    <div class="px-3 md:px-4 py-1.5 md:py-2 text-white bg-teal-600 rounded-lg">
                        <span class="text-xs font-medium md:text-sm">
                            {{-- Menggunakan <a> untuk link Beranda yang aktif --}}
                            <a href="{{ $berandaRoute }}" class="transition duration-150 hover:text-teal-200">Beranda</a> 
                            > Penginapan
                        </span>
                    </div>
                </div>
            </div>

        </div>
        
        {{-- Search Form - Floating Box --}}
        <div class="absolute bottom-0 left-1/2 transform -translate-x-1/2 translate-y-1/2 w-[95%] sm:w-[90%] max-w-5xl px-2 sm:px-4 md:px-0 z-10">
            <div class="p-4 bg-white shadow-xl md:p-6 rounded-xl">
                <form method="GET" action="{{ $penginapanListRoute }}" id="mainFilterForm">
                    
                    {{-- Main Filter Grid: 1 kolom di mobile, 2 kolom di sm, 4 kolom di md --}}
                    <div class="grid grid-cols-1 gap-3 sm:grid-cols-2 md:grid-cols-4 sm:gap-4">
                        
                        {{-- Tipe Penginapan --}}
                        <div class="col-span-1">
                            <label class="block mb-1 text-xs font-medium text-gray-700 md:text-sm">Tipe Penginapan:</label>
                            <select name="tipe" class="w-full px-2 py-2 text-xs border border-gray-300 rounded-lg appearance-none md:px-3 md:text-sm bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">Semua Tipe</option>
                                @foreach($all_tipes as $tipe)
                                    <option value="{{ $tipe }}" {{ request('tipe') == $tipe ? 'selected' : '' }}>
                                        {{ $tipe }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Periode Harga --}}
                        <div class="col-span-1">
                            <label class="block mb-1 text-xs font-medium text-gray-700 md:text-sm">Periode:</label>
                            <select name="periode" class="w-full px-2 py-2 text-xs border border-gray-300 rounded-lg appearance-none md:px-3 md:text-sm bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">Semua Periode</option>
                                @foreach($periode_options as $periode)
                                    <option value="{{ $periode }}" {{ request('periode') == $periode ? 'selected' : '' }}>
                                        {{ $periode }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Kota --}}
                        <div class="col-span-1">
                            <label class="block mb-1 text-xs font-medium text-gray-700 md:text-sm">Kota:</label>
                            <select name="kota" class="w-full px-2 py-2 text-xs border border-gray-300 rounded-lg appearance-none md:px-3 md:text-sm bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                <option value="">Semua Kota</option>
                                @foreach($all_kotas as $kota)
                                    <option value="{{ $kota }}" {{ request('kota') == $kota ? 'selected' : '' }}>
                                        {{ $kota }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        {{-- Pencarian --}}
                        <div class="col-span-1">
                            <label class="block mb-1 text-xs font-medium text-gray-700 md:text-sm">Pencarian:</label>
                            <input type="text" name="search" value="{{ request('search') }}" placeholder="Cari penginapan..."
                                class="w-full px-2 py-2 text-xs border border-gray-300 rounded-lg md:px-3 md:text-sm bg-gray-50 focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                        </div>
                    </div>

                    {{-- Advanced Filter Toggle --}}
                    <div class="pt-3 mt-3 border-t md:pt-4 md:mt-4">
                        <button type="button" id="toggleAdvanced"
                                class="flex items-center text-xs font-medium text-teal-600 md:text-sm hover:text-teal-700 touch-manipulation">
                            <span>Filter Lanjutan</span>
                            <svg class="w-4 h-4 ml-2 transition-transform transform" id="advancedArrow" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M19 9l-7 7-7-7"></path>
                            </svg>
                        </button>

                        {{-- Advanced Filters (Isi sama) --}}
                        <div id="advancedFilters" class="hidden p-3 mt-3 rounded-lg bg-gray-50">
                            <div class="grid grid-cols-1 gap-3">
                                <div>
                                    <label class="block mb-2 text-xs font-medium text-gray-700 md:text-sm">Rentang Harga:</label>
                                    
                                    <div class="grid grid-cols-2 gap-2 mb-3 md:gap-3">
                                        <div>
                                            <label class="block mb-1 text-xs text-gray-600">Harga Minimum</label>
                                            <input type="number" 
                                                            name="harga_min" 
                                                            value="{{ request('harga_min') }}" 
                                                            placeholder="500000"
                                                            min="0"
                                                            step="50000"
                                                            class="w-full px-2 py-2 text-xs border border-gray-300 rounded-lg md:px-3 md:text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                        </div>
                                        <div>
                                            <label class="block mb-1 text-xs text-gray-600">Harga Maksimum</label>
                                            <input type="number" 
                                                            name="harga_max" 
                                                            value="{{ request('harga_max') }}" 
                                                            placeholder="2000000"
                                                            min="0"
                                                            step="50000"
                                                            class="w-full px-2 py-2 text-xs border border-gray-300 rounded-lg md:px-3 md:text-sm focus:ring-2 focus:ring-teal-500 focus:border-teal-500">
                                        </div>
                                    </div>

                                    <div class="flex flex-wrap gap-2">
                                        <span class="self-center mr-1 text-xs text-gray-600 md:mr-2">Cepat:</span>
                                        <button type="button" onclick="setPrice(0, 500000)" 
                                                            class="px-2 py-1 text-xs transition-colors bg-white border border-gray-300 rounded-lg md:px-3 hover:bg-teal-50 hover:border-teal-500">
                                            &lt; 500rb
                                        </button>
                                        <button type="button" onclick="setPrice(500000, 1000000)" 
                                                            class="px-2 py-1 text-xs transition-colors bg-white border border-gray-300 rounded-lg md:px-3 hover:bg-teal-50 hover:border-teal-500">
                                            500rb - 1jt
                                        </button>
                                        <button type="button" onclick="setPrice(1000000, 2000000)" 
                                                            class="px-2 py-1 text-xs transition-colors bg-white border border-gray-300 rounded-lg md:px-3 hover:bg-teal-50 hover:border-teal-500">
                                            1jt - 2jt
                                        </button>
                                        <button type="button" onclick="setPrice(2000000, 5000000)" 
                                                            class="px-2 py-1 text-xs transition-colors bg-white border border-gray-300 rounded-lg md:px-3 hover:bg-teal-50 hover:border-teal-500">
                                            2jt - 5jt
                                        </button>
                                        <button type="button" onclick="setPrice(5000000, null)" 
                                                            class="px-2 py-1 text-xs transition-colors bg-white border border-gray-300 rounded-lg md:px-3 hover:bg-teal-50 hover:border-teal-500">
                                            &gt; 5jt
                                        </button>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Button Pencarian (Kotak Pendek di Desktop) --}}
                    <div class="grid grid-cols-2 gap-3 mt-3 md:mt-4 lg:flex lg:justify-center lg:space-x-4 lg:grid-cols-none">
                        <button type="submit"
                            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-orange-500 rounded-lg lg:w-48 md:px-6 md:text-base hover:bg-orange-600">
                            <svg class="w-4 h-4 mr-2" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                            Pencarian
                        </button>
                        
                        <a href="{{ $penginapanListRoute }}"
                            class="flex items-center justify-center w-full px-4 py-2 text-sm font-medium text-white transition-colors duration-200 bg-gray-500 rounded-lg lg:w-48 md:px-6 md:text-base hover:bg-gray-600">
                            Reset Filter
                        </a>
                    </div>
                </div>

                {{-- Search --}}
                <div class="p-3 mb-4 rounded bg-gray-50">
                    <form action="{{ route('admin.penginapan.index') }}" method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-4">
                        <div class="md:col-span-3">
                            <label for="search" class="text-xs font-medium text-gray-700">Cari Artikel</label>
                            <input type="text" name="search" id="search" value="{{ request('search') }}"
                                   class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm"
                                   placeholder="Ketik nama, kota, tipe, status, atau author...">
                        </div>
                        <div class="flex items-end gap-2">
                            <button type="submit" class="w-full px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">Cari</button>
                            <a href="{{ route('admin.penginapan.index') }}" class="w-full px-3 py-1 text-xs font-semibold text-center text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Reset</a>
                        </div>
                    </form>
                </div>

                {{-- Alerts --}}
                @if(session('success'))
                    <div class="px-3 py-2 mb-3 text-sm text-green-700 bg-green-100 border border-green-200 rounded" role="alert">
                        {{ session('success') }}
                    </div>
                @endif
                @if(session('error'))
                    <div class="px-3 py-2 mb-3 text-sm text-red-700 bg-red-100 border border-red-200 rounded" role="alert">
                        {{ session('error') }}
                    </div>
                @endif

                {{-- Table --}}
                <div class="overflow-x-auto">
                    <table class="min-w-full text-sm border border-gray-200 table-fixed">
                        <thead class="bg-gray-100">
                            <tr>
                                <th class="w-10 px-2 py-2 text-center border">
                                    <input type="checkbox" x-model="selectAll" @click="toggleSelectAll" class="rounded">
                                </th>
                                <th class="w-12 px-2 py-2 text-center border">No</th>
                                <th class="px-3 py-2 text-left border w-28">Thumbnail</th>
                                <th class="px-3 py-2 text-left border">Nama Artikel</th>
                                @if(Auth::user()->role === 'admin')
                                    <th class="px-3 py-2 text-left border w-44">Author</th>
                                @endif
                                <th class="w-20 px-3 py-2 text-center border">Views</th>
                                <th class="px-3 py-2 text-left border w-28">Status</th>
                                <th class="w-40 px-3 py-2 text-left border">Aksi</th>
                            </tr>
                        </thead>

                        <tbody>
                            @forelse ($all_penginapan as $item)
                                <tr class="hover:bg-gray-50">
                                    <td class="px-2 py-2 text-center align-middle border">
                                        <input type="checkbox" value="{{ $item->id }}" x-model="selectedIds" @change="updateSelectAllState()" class="rounded item-checkbox">
                                    </td>

                                    <td class="px-2 py-2 text-xs text-center align-middle border">
                                        {{ ($all_penginapan->currentPage() - 1) * $all_penginapan->perPage() + $loop->iteration }}
                                    </td>

                                    <td class="px-3 py-2 align-middle border">
                                        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->nama }}" class="object-cover w-20 h-12 rounded-sm">
                                    </td>

                                    <td class="px-3 py-2 align-top border">
                                        @if($item->status == 'diterima')
                                            <a href="{{ route('penginapan.detail', $item->slug) }}" target="_blank" class="text-sm font-medium text-indigo-600 hover:underline">
                                                {{ $item->nama }}
                                            </a>
                                        @else
                                            <div class="text-sm font-medium text-gray-800">{{ $item->nama }}</div>
                                        @endif

                                        @if($item->status == 'revisi' && $item->catatan_revisi)
                                            <div class="max-w-xs px-2 py-1 mt-2 text-xs text-red-800 break-words rounded bg-red-50">
                                                <strong>Catatan Revisi:</strong> {{ Str::limit($item->catatan_revisi, 120) }}
                                            </div>
                                        @endif
                                    </td>

                                    @if(Auth::user()->role === 'admin')
                                        <td class="px-3 py-2 align-top border">
                                            <div class="flex items-center justify-between gap-3">
                                                <div class="flex items-center min-w-0 gap-3">
                                                    <img src="{{ $item->author->profile_photo_path ? asset('storage/' . $item->author->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($item->author->name) . '&color=FFFFFF&background=2563EB' }}"
                                                        alt="{{ $item->author->name }}" class="flex-shrink-0 object-cover w-8 h-8 rounded-full">
                                                    <div class="min-w-0">
                                                        <div class="text-sm font-medium text-gray-900 truncate">{{ $item->author->name }}</div>
                                                        <div class="text-xs text-gray-500 truncate">{{ $item->author->email ?? '' }}</div>
                                                    </div>
                                                </div>

                                                @php
                                                    switch ($item->author->role) {
                                                        case 'admin': $roleClass = 'bg-indigo-100 text-indigo-800'; break;
                                                        case 'platinum': $roleClass = 'bg-gray-800 text-white'; break;
                                                        case 'gold': $roleClass = 'bg-yellow-100 text-yellow-800'; break;
                                                        case 'silver': $roleClass = 'bg-slate-100 text-slate-800'; break;
                                                        default: $roleClass = 'bg-green-100 text-green-800';
                                                    }
                                                @endphp

                                                <span class="px-2 py-1 text-xs font-semibold rounded-full {{ $roleClass }} flex-shrink-0 whitespace-nowrap">
                                                    {{ ucfirst($item->author->role) }}
                                                </span>
                                            </div>
                                        </td>
                                    @endif

                                    <td class="px-3 py-2 text-xs text-center align-top border">{{ $item->views }}</td>

                                    <td class="px-3 py-2 align-top border">
                                        @can('admin')
                                            {{-- dispatch routeKey (getRouteKey) so binding works whether slug or id --}}
                                            <button
                                                @click="$dispatch('open-status-modal', { routeKey: '{{ $item->getRouteKey() }}', currentStatus: '{{ $item->status }}' })"
                                                class="inline-flex items-center gap-2 px-1 py-1 text-xs rounded-sm group hover:bg-slate-50 focus:outline-none">
                                                <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full
                                                      @if($item->status == 'diterima') bg-green-100 text-green-800
                                                      @elseif($item->status == 'verifikasi') bg-yellow-100 text-yellow-800
                                                      @else bg-red-100 text-red-800 @endif">
                                                    {{ ucfirst($item->status) }}
                                                </span>
                                                <span class="text-xs text-gray-400 group-hover:text-gray-600">ubah</span>
                                            </button>
                                        @else
                                            <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full
                                                  @if($item->status == 'diterima') bg-green-100 text-green-800
                                                  @elseif($item->status == 'verifikasi') bg-yellow-100 text-yellow-800
                                                  @else bg-red-100 text-red-800 @endif">
                                                {{ ucfirst($item->status) }}
                                            </span>
                                        @endcan
                                    </td>

                                    {{-- Actions dropdown --}}
                                    <td class="px-3 py-2 align-top border">
                                        <div class="relative" x-data="{
                                                open: false,
                                                style: {},
                                                positionMenu() {
                                                    const btn = this.$refs.btn;
                                                    if (!btn) return;
                                                    const r = btn.getBoundingClientRect();
                                                    const gap = 8;
                                                    const MIN_W = 180;
                                                    const CAP_W = Math.min(360, window.innerWidth - 24);
                                                    const desiredW = Math.min(Math.max(r.width, MIN_W), CAP_W);

                                                    // vertical position choose below/above
                                                    const EST_H = 160;
                                                    const belowSpace = window.innerHeight - r.bottom;
                                                    const aboveSpace = r.top;
                                                    let top;
                                                    if (belowSpace >= EST_H + gap) {
                                                        top = r.bottom + gap;
                                                        this.maxH = null;
                                                    } else if (aboveSpace >= EST_H + gap) {
                                                        top = r.top - EST_H - gap;
                                                        this.maxH = null;
                                                    } else {
                                                        if (belowSpace >= aboveSpace) {
                                                            top = r.bottom + gap;
                                                            this.maxH = Math.max(belowSpace - gap, 80);
                                                        } else {
                                                            top = Math.max(gap, r.top - Math.max(aboveSpace - gap, 80) - gap);
                                                            this.maxH = Math.max(aboveSpace - gap, 80);
                                                        }
                                                    }

                                                    let left = r.right - desiredW;
                                                    left = Math.min(Math.max(left, 8), Math.max(window.innerWidth - desiredW - 8, 8));

                                                    this.style = {
                                                        position: 'fixed',
                                                        top: Math.round(top) + 'px',
                                                        left: Math.round(left) + 'px',
                                                        width: Math.round(desiredW) + 'px',
                                                        boxSizing: 'border-box',
                                                        overflowX: 'hidden',
                                                        whiteSpace: 'normal',
                                                        wordBreak: 'break-word'
                                                    };
                                                },
                                                openMenu() {
                                                    this.open = !this.open;
                                                    if (this.open) {
                                                        this.positionMenuBound = this.positionMenu.bind(this);
                                                        this.positionMenu();
                                                        window.addEventListener('resize', this.positionMenuBound);
                                                        window.addEventListener('scroll', this.positionMenuBound, true);
                                                    } else {
                                                        if (this.positionMenuBound) {
                                                            window.removeEventListener('resize', this.positionMenuBound);
                                                            window.removeEventListener('scroll', this.positionMenuBound, true);
                                                            this.positionMenuBound = null;
                                                        }
                                                    }
                                                },
                                                closeMenu() {
                                                    this.open = false;
                                                    if (this.positionMenuBound) {
                                                        window.removeEventListener('resize', this.positionMenuBound);
                                                        window.removeEventListener('scroll', this.positionMenuBound, true);
                                                        this.positionMenuBound = null;
                                                    }
                                                }
                                            }"
                                             @click.outside="closeMenu()"
                                        >
                                            <button x-ref="btn" @click="openMenu()" type="button"
                                                    class="inline-flex items-center px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded hover:bg-gray-200">
                                                <span class="material-symbols-outlined !text-base mr-1">settings</span>
                                                <span>Pilihan</span>
                                            </button>

                                            {{-- Dropdown menu (fixed positioned) --}}
                                            <div x-show="open" x-cloak x-transition x-ref="menu"
                                                 :style="style"
                                                 class="z-50 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                                                <div class="py-1">
                                                    @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'member' && $item->status === 'revisi'))
                                                        <a href="{{ route('admin.penginapan.edit', $item) }}" class="block px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">Edit</a>
                                                    @endif

                                                    @can('admin')
                                                        <button type="button" @click="closeMenu(); $dispatch('open-author-modal', { routeKey: '{{ $item->getRouteKey() }}' })" class="w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">Ganti Author</button>

                                                        <button type="button" @click="closeMenu(); $dispatch('open-status-modal', { routeKey: '{{ $item->getRouteKey() }}', currentStatus: '{{ $item->status }}' })" class="w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">Ubah Status</button>
                                                    @endcan

                                                    <form action="{{ route('admin.penginapan.destroy', $item) }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                                                        @csrf
                                                        @method('DELETE')
                                                        <button type="submit" class="w-full px-4 py-2 text-sm text-left text-red-700 hover:bg-gray-100">Hapus</button>
                                                    </form>
                                                </div>
                                            </div>
                                        </div>
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="{{ Auth::user()->role === 'admin' ? '8' : '7' }}" class="px-4 py-12 text-center text-gray-500 border">
                                        Tidak ada data artikel yang cocok dengan pencarian.
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>

                {{-- Pagination --}}
                <div class="mt-4">
                    {{ $all_penginapan->links() }}
                </div>
            </div>
        </div>
    </div>

    {{-- Results Section --}}
    {{-- PENYESUAIAN PADDING-TOP KRITIS UNTUK LG (DESKTOP/NEST HUB): Meningkatkan lg:pt untuk memberi ruang --}}
    <div class="py-6 lg:py-12 bg-gray-50 pt-[18rem] sm:pt-[12rem] md:pt-[10rem] lg:pt-[10rem]"> 
        <div class="px-3 mx-auto sm:px-4 lg:px-6 max-w-7xl">
            
            {{-- Active Filters Display --}}
            @if(request()->hasAny(['tipe', 'kota', 'harga_min', 'harga_max', 'periode', 'fasilitas', 'search']))
                <div class="p-3 mb-4 bg-white rounded-lg shadow-sm lg:p-4 lg:mb-6">
                    <div class="flex items-center justify-between mb-2">
                        <h3 class="text-xs font-medium text-gray-700 md:text-sm">Filter Aktif:</h3>
                        <a href="{{ $penginapanListRoute }}" class="text-xs text-red-600 md:text-sm hover:text-red-700">Hapus Semua</a>
                    </div>
                    <div class="flex flex-wrap gap-2">
                        @if(request('tipe'))
                            <span class="px-2 py-1 text-xs text-blue-800 bg-blue-100 rounded-full md:px-3 md:text-sm">
                                Tipe: {{ request('tipe') }}
                            </span>
                        @endif
                        @if(request('kota'))
                            <span class="px-2 py-1 text-xs text-green-800 bg-green-100 rounded-full md:px-3 md:text-sm">
                                Kota: {{ request('kota') }}
                            </span>
                        @endif
                        @if(request('periode'))
                            <span class="px-2 py-1 text-xs text-purple-800 bg-purple-100 rounded-full md:px-3 md:text-sm">
                                Periode: {{ request('periode') }}
                            </span>
                        @endif
                        @if(request('search'))
                            <span class="px-2 py-1 text-xs text-yellow-800 bg-yellow-100 rounded-full md:px-3 md:text-sm">
                                Pencarian: "{{ request('search') }}"
                            </span>
                        @endif
                        @if(request('harga_min') || request('harga_max'))
                            <span class="px-2 py-1 text-xs text-orange-800 bg-orange-100 rounded-full md:px-3 md:text-sm">
                                Harga: Rp {{ number_format(request('harga_min', 0)) }} - Rp {{ number_format(request('harga_max', 999999999)) }}
                            </span>
                        @endif
                    </div>
                </div>
            @endif

            {{-- Results Header --}}
            <div class="flex flex-col gap-3 mb-6 sm:flex-row sm:items-center sm:justify-between lg:mb-8">
                <div>
                    <h2 class="text-lg font-bold text-gray-800 md:text-2xl"></h2>
                    <p class="mt-2 mb-4 text-base font-semibold text-gray-800 md:text-lg md:mb-0">
                        {{ $penginapan->total() }} Penginapan Ditemukan di Jogja
                    </p>
                </div>
                <div>
                    <form method="GET" action="{{ $penginapanListRoute }}" class="flex items-center space-x-2">
                        @foreach(request()->except(['sort_by']) as $key => $value)
                            @if(is_array($value))
                                @foreach($value as $v)
                                    <input type="hidden" name="{{ $key }}[]" value="{{ $v }}">
                                @endforeach
                            @else
                                <input type="hidden" name="{{ $key }}" value="{{ $value }}">
                            @endif
                        @endforeach

                        <span class="text-xs text-gray-600 md:text-sm">Sort by:</span>
                        <select name="sort_by" onchange="this.form.submit()"
                                class="px-2 md:px-4 py-1.5 md:py-2 text-xs md:text-sm bg-white border border-gray-300 rounded-lg focus:ring-2 focus:ring-teal-500 focus:border-teal-500 appearance-none pr-8">
                            <option value="created_at" {{ request('sort_by', 'created_at') == 'created_at' ? 'selected' : '' }}>Terbaru</option>
                            <option value="harga" {{ request('sort_by') == 'harga' ? 'selected' : '' }}>Termurah</option>
                            <option value="nama" {{ request('sort_by') == 'nama' ? 'selected' : '' }}>Abjad (A-Z)</option>
                            <option value="views" {{ request('sort_by') == 'views' ? 'selected' : '' }}>Rekomendasi</option>
                        </select>
                    </form>
                </div>
            </div>
            
            {{-- Grid Penginapan --}}
            <div class="grid grid-cols-1 gap-4 sm:grid-cols-2 lg:grid-cols-3 xl:grid-cols-4 lg:gap-6">
                @forelse ($penginapan as $index => $item)
                    <div class="overflow-hidden transition-shadow duration-300 bg-white shadow-lg rounded-2xl hover:shadow-xl group">

                        <div class="relative h-48 overflow-hidden sm:h-44 md:h-48">
                            <a href="{{ route('penginapan.detail', $item->slug) }}">
                                @if($item->thumbnail)
                                    <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->nama }}"
                                            class="object-cover w-full h-full transition-transform duration-300 group-hover:scale-105">
                                @else
                                    <div class="flex items-center justify-center w-full h-full bg-gradient-to-br from-gray-100 to-gray-200">
                                        <svg class="w-12 h-12 text-gray-400" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                            <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                        d="M4 16l4.586-4.586a2 2 0 012.828 0L16 16m-2-2l1.586-1.586a2 2 0 012.828 0L20 14m-6-6h.01M6 20h12a2 2 0 002-2V6a2 2 0 00-2-2H6a2 2 0 00-2 2v12a2 2 0 002 2z">
                                            </path>
                                        </svg>
                                    </div>
                                @endif
                            </a>

                            @if($index < 8)
                                <div class="absolute top-2 left-2">
                                    <div class="flex items-center px-2 py-1 text-xs font-medium text-white bg-orange-500 rounded-lg md:px-3">
                                        <svg class="w-3 h-3 mr-1" fill="currentColor" viewBox="0 0 20 20">
                                            <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                        </svg>
                                        Rekomendasi
                                    </div>
                                </div>
                            @endif
                        </div>

                        {{-- Content --}}
                        <div class="p-3 md:p-4 lg:p-5">
                            <h3 class="mb-2 text-sm font-bold text-gray-800 transition-colors md:text-base lg:text-lg line-clamp-2 group-hover:text-teal-600">
                                <a href="{{ route('penginapan.detail', $item) }}">{{ $item->nama }}</a>
                            </h3>
                            
                            <div class="flex items-center mb-1.5 text-gray-600">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path fill-rule="evenodd" d="M5.05 4.05a7 7 0 119.9 9.9L10 18.9l-4.95-4.95a7 7 0 010-9.9zM10 11a2 2 0 100-4 2 2 0 000 4z" clip-rule="evenodd"></path>
                                </svg>
                                <span class="text-xs md:text-sm">{{ $item->kota }}</span>
                            </div>

                            <div class="flex items-center mb-2 text-gray-600">
                                <svg class="w-3.5 h-3.5 mr-1.5" fill="currentColor" viewBox="0 0 20 20">
                                    <path d="M10.707 2.293a1 1 0 00-1.414 0l-7 7a1 1 0 001.414 1.414L4 10.414V17a1 1 0 001 1h2a1 1 0 001-1v-2a1 1 0 011-1h2a1 1 0 011 1v2a1 1 0 001 1h2a1 1 0 001-1v-6.586l.293.293a1 1 0 001.414-1.414l-7-7z"></path>
                                </svg>
                                <span class="text-xs md:text-sm">{{ $item->tipe }}</span>
                            </div>

                            <div class="flex items-center justify-between mt-3">
                                <div class="px-2 md:px-3 py-1.5 text-white bg-teal-600 clip-slant">
                                    <div class="flex flex-col leading-tight">
                                        <span class="text-[10px] md:text-xs">Harian</span>
                                        <span class="text-sm font-bold md:text-base lg:text-lg">
                                            Rp{{ number_format($item->harga, 0, ',', '.') }}
                                        </span>
                                    </div>
                                </div>
                                <div class="flex items-center space-x-1 text-xs font-medium text-gray-700 md:text-sm">
                                    <svg class="w-4 h-4" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M15 12a3 3 0 11-6 0 3 3 0 016 0z" />
                                        <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2"
                                                    d="M2.458 12C3.732 7.943 7.523 5 12 5c4.478 0 8.268 2.943 9.542 7-1.274 4.057-5.064 7-9.542 7-4.477 0-8.268-2.943-9.542-7z" />
                                    </svg>
                                    <span>{{ $item->views }}</span>
                                </div>
                            </div>

                            @if($item->rating > 0)
                                <div class="flex items-center mt-2 text-gray-600">
                                    <div class="flex items-center">
                                        @for($i = 1; $i <= 5; $i++)
                                            <svg class="w-3 h-3 md:w-4 md:h-4 {{ $i <= $item->rating ? 'text-yellow-400' : 'text-gray-300' }}" fill="currentColor" viewBox="0 0 20 20">
                                                <path d="M9.049 2.927c.3-.921 1.603-.921 1.902 0l1.07 3.292a1 1 0 00.95.69h3.462c.969 0 1.371 1.24.588 1.81l-2.8 2.034a1 1 0 00-.364 1.118l1.07 3.292c.3.921-.755 1.688-1.54 1.118l-2.8-2.034a1 1 0 00-1.175 0l-2.8 2.034c-.784.57-1.838-.197-1.539-1.118l1.07-3.292a1 1 0 00-.364-1.118L2.98 8.72c-.783-.57-.38-1.81.588-1.81h3.461a1 1 0 00.951-.69l1.07-3.292z"></path>
                                            </svg>
                                        @endfor
                                        <span class="ml-2 text-xs md:text-sm">({{ $item->rating }})</span>
                                    </div>
                                </div>
                            @endif
                        </div>
                    </div>
                @empty
                    <div class="py-12 text-center col-span-full">
                        <div class="mb-6">
                            <svg class="w-12 h-12 mx-auto text-gray-300 lg:w-16 lg:h-16" fill="none" stroke="currentColor" viewBox="0 0 24 24">
                                <path stroke-linecap="round" stroke-linejoin="round" stroke-width="2" d="M21 21l-6-6m2-5a7 7 0 11-14 0 7 7 0 0114 0z"></path>
                            </svg>
                        </div>
                        <h3 class="mb-2 text-base font-semibold text-gray-700 md:text-lg lg:text-xl">Tidak ada penginapan ditemukan</h3>
                        <p class="mb-4 text-sm text-gray-500 lg:text-base">Coba ubah filter pencarian atau hapus beberapa kriteria</p>
                        <a href="{{ $penginapanListRoute }}"
                           class="px-6 py-2 text-sm text-white transition-colors bg-teal-600 rounded-lg lg:text-base hover:bg-teal-700">
                            Lihat Semua Penginapan
                        </a>
                    </div>
                @endforelse
            </div>

            {{-- Pagination --}}
            @if ($penginapan->hasPages())
                <div class="flex justify-center mt-8 lg:mt-12">
                    <nav role="navigation" aria-label="Pagination" class="flex items-center space-x-1 md:space-x-2">

                        @if ($penginapan->onFirstPage())
                            <span class="px-2 md:px-4 py-1.5 md:py-2 text-xs md:text-base text-gray-500 rounded-lg cursor-not-allowed">
                                Sebelumnya
                            </span>
                        @else
                            <a href="{{ $penginapan->previousPageUrl() }}" rel="prev" 
                               class="px-2 md:px-4 py-1.5 md:py-2 text-xs md:text-base text-gray-800 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Sebelumnya
                            </a>
                        @endif

                        <div class="flex items-center space-x-1 md:space-x-2">
                            @foreach ($penginapan->links()->elements as $element)
                                @if (is_array($element))
                                    @foreach ($element as $page => $url)
                                        @if ($page == $penginapan->currentPage())
                                            <span class="flex items-center justify-center w-8 h-8 text-xs font-semibold text-white bg-teal-600 rounded-lg shadow-md md:w-10 md:h-10 md:text-base">
                                                {{ $page }}
                                            </span>
                                        @else
                                            <a href="{{ $url }}" 
                                               class="flex items-center justify-center w-8 h-8 text-xs font-medium text-gray-800 transition-colors bg-gray-100 rounded-lg md:w-10 md:h-10 md:text-base hover:bg-gray-200">
                                                {{ $page }}
                                            </a>
                                        @endif
                                    @endforeach
                                @endif

                                @if (is_string($element))
                                    <span class="flex items-center justify-center w-8 h-8 text-xs font-medium text-gray-500 md:w-10 md:h-10 md:text-base">
                                        {{ $element }}
                                    </span>
                                @endif
                            @endforeach
                        </div>

                        @if ($penginapan->hasMorePages())
                            <a href="{{ $penginapan->nextPageUrl() }}" rel="next" 
                               class="px-2 md:px-4 py-1.5 md:py-2 text-xs md:text-base text-gray-800 bg-gray-100 rounded-lg hover:bg-gray-200 transition-colors">
                                Selanjutnya
                            </a>
                        @else
                            <span class="px-2 md:px-4 py-1.5 md:py-2 text-xs md:text-base text-gray-500 bg-gray-50 rounded-lg cursor-not-allowed">
                                Selanjutnya
                            </span>
                        @endif

                    </nav>
                </div>
            @endif

        </div>
    @endcan

    {{-- Status Modal (AJAX PATCH using routeKey) --}}
    @can('admin')
        <div x-data="{
                open:false,
                routeKey:null,
                status:null,
                loading:false,
                error:null,
                openFor(event) {
                    this.routeKey = event.detail.routeKey;
                    this.status = event.detail.currentStatus ?? null;
                    this.error = null;
                    this.open = true;
                },
                buildUrl() {
                    const tpl = document.getElementById('route-templates')?.dataset?.penginapanStatusTemplate || '/admin/penginapan/__ID__/status';
                    return tpl.replace('__ID__', encodeURIComponent(this.routeKey || ''));
                },
                async submitStatus() {
                    if (!this.routeKey || !this.status) {
                        this.error = 'Item atau status tidak valid.';
                        return;
                    }
                    this.loading = true;
                    this.error = null;

                    const token = document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '';
                    const url = this.buildUrl();
                    const body = { status: this.status };
                    if (this.status === 'revisi') body.catatan_revisi = this.$refs.catatan ? this.$refs.catatan.value : '';

                    try {
                        const res = await fetch(url, {
                            method: 'PATCH',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            credentials: 'same-origin',
                            body: JSON.stringify(body)
                        });

                        console.log('PATCH', url, '=>', res.status);

                        if (!res.ok) {
                            const txt = await res.text();
                            console.error('Response body:', txt);
                            this.error = `Gagal (HTTP ${res.status}).`;
                            this.loading = false;
                            return;
                        }

                        this.loading = false;
                        this.open = false;
                        // refresh to show updated status
                        window.location.reload();
                    } catch (e) {
                        console.error(e);
                        this.error = 'Terjadi error jaringan.';
                        this.loading = false;
                    }
                }
            }"
             x-on:open-status-modal.window="openFor($event)"
             x-cloak>
            <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div x-show="open" x-transition class="fixed inset-0 bg-gray-500 bg-opacity-60"></div>

                <div x-show="open" x-transition @click.outside="open = false" class="relative w-full max-w-md bg-white rounded-md shadow-lg">
                    <div class="p-4">
                        <h3 class="text-base font-medium text-gray-900">Ubah Status</h3>

                        <div class="mt-3 space-y-3">
                            <div>
                                <label class="block text-xs font-medium text-gray-700">Status Baru</label>
                                <select x-model="status" class="block w-full mt-1 text-sm border-gray-300 rounded" required>
                                    <option value="diterima">Diterima</option>
                                    <option value="verifikasi">Verifikasi</option>
                                    <option value="revisi">Revisi</option>
                                </select>
                            </div>

                            <div x-show="status === 'revisi'">
                                <label class="block text-xs font-medium text-gray-700">Catatan Revisi</label>
                                <textarea x-ref="catatan" rows="3" class="block w-full mt-1 text-sm border-gray-300 rounded"></textarea>
                            </div>

                            <template x-if="error">
                                <div class="p-2 text-sm text-red-700 bg-red-100 rounded" x-text="error"></div>
                            </template>
                        </div>
                    </div>

                    <div class="flex justify-end px-4 py-3 space-x-2 bg-gray-50">
                        <button type="button" @click="open=false" class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                        <button type="button" @click="submitStatus()" :disabled="loading" class="px-3 py-1 text-xs font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700">
                            <span x-show="!loading">Simpan</span>
                            <span x-show="loading">Menyimpan...</span>
                        </button>
                    </div>
                </div>
            </div>
        </div>
    @endcan

</x-app-layout>
    <style>
        select {
            background-image: url("data:image/svg+xml,%3csvg xmlns='http://www.w3.org/2000/svg' fill='none' viewBox='0 0 20 20'%3e%3cpath stroke='%236b7280' stroke-linecap='round' stroke-linejoin='round' stroke-width='1.5' d='M6 8l4 4 4-4'/%3e%3c/svg%3e");
            background-position: right 0.5rem center;
            background-repeat: no-repeat;
            background-size: 1.5em 1.5em;
            padding-right: 2.5rem;
        }

        .clip-slant {
            clip-path: polygon(0 0, 90% 0, 100% 100%, 0% 100%);
            border-bottom-left-radius: 0.5rem;
        }

        .line-clamp-2 {
            display: -webkit-box;
            -webkit-line-clamp: 2;
            line-clamp: 2;
            -webkit-box-orient: vertical;
            overflow: hidden;
        }
    </style>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toggleBtn = document.getElementById('toggleAdvanced');
            const advancedFilters = document.getElementById('advancedFilters');
            const arrow = document.getElementById('advancedArrow');

            const urlParams = new URLSearchParams(window.location.search);
            const hargaMin = urlParams.get('harga_min');
            const hargaMax = urlParams.get('harga_max');
            
            let isAdvancedFilterActive = (hargaMin || hargaMax);
            
            if (toggleBtn && advancedFilters && arrow) {
                
                // Jika ada filter lanjutan yang aktif saat loading, tampilkan
                if (isAdvancedFilterActive) {
                    advancedFilters.classList.remove('hidden');
                    arrow.classList.add('rotate-180');
                }
                
                // Tambahkan event listener untuk toggle
                toggleBtn.addEventListener('click', function() {
                    advancedFilters.classList.toggle('hidden');
                    arrow.classList.toggle('rotate-180');
                });
            }
        });

        function setPrice(min, max) {
            const minInput = document.querySelector('input[name="harga_min"]');
            const maxInput = document.querySelector('input[name="harga_max"]');
            
            if (minInput) minInput.value = min !== null ? min : '';
            if (maxInput) maxInput.value = max !== null ? max : '';
        }
    </script>
</x-guest-layout>
