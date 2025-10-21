{{-- resources/views/admin/wisata/index/_row.blade.php --}}
@php
    // expects $item, $loop, $all_wisata variables
@endphp

<tr class="hover:bg-gray-50">
    <td class="px-2 py-2 text-center align-middle border">
        <input type="checkbox" value="{{ $item->id }}" x-model="selectedIds" @change="updateSelectAllState()"
            class="rounded item-checkbox">
    </td>

    <td class="px-2 py-2 text-xs text-center align-middle border">
        {{ ($all_wisata->currentPage() - 1) * $all_wisata->perPage() + $loop->iteration }}
    </td>

    <td class="px-3 py-2 align-middle border">
        <img src="{{ asset('storage/' . $item->thumbnail) }}" alt="{{ $item->nama }}"
            class="object-cover w-20 h-12 rounded-sm">
    </td>

    <td class="px-3 py-2 align-top border">
        @if($item->status == 'diterima')
            <a href="{{ route('wisata.detail', $item->slug) }}" target="_blank"
                class="text-sm font-medium text-indigo-600 hover:underline">
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
                        case 'admin':
                            $roleClass = 'bg-indigo-100 text-indigo-800';
                            break;
                        case 'platinum':
                            $roleClass = 'bg-gray-800 text-white';
                            break;
                        case 'gold':
                            $roleClass = 'bg-yellow-100 text-yellow-800';
                            break;
                        case 'silver':
                            $roleClass = 'bg-slate-100 text-slate-800';
                            break;
                        default:
                            $roleClass = 'bg-green-100 text-green-800';
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

                    const EST_H = 160;
                    const belowSpace = window.innerHeight - r.bottom;
                    const aboveSpace = r.top;
                    let top;
                    if (belowSpace >= EST_H + gap) {
                        top = r.bottom + gap;
                    } else if (aboveSpace >= EST_H + gap) {
                        top = r.top - EST_H - gap;
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
            }" @click.outside="closeMenu()">
            <button x-ref="btn" @click="openMenu()" type="button"
                class="inline-flex items-center px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded hover:bg-gray-200">
                <span class="material-symbols-outlined !text-base mr-1">settings</span>
                <span>Pilihan</span>
            </button>

            <div x-show="open" x-cloak x-transition x-ref="menu" :style="style"
                class="z-50 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5">
                <div class="py-1">
                    @if(Auth::user()->role === 'admin' || (Auth::user()->role === 'member' && $item->status === 'revisi'))
                        <a href="{{ route('admin.wisata.edit', $item) }}"
                            class="block px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">Edit</a>
                    @endif

                    @can('admin')
                        <button type="button"
                            @click="closeMenu(); $dispatch('open-author-modal', { routeKey: '{{ $item->getRouteKey() }}' })"
                            class="w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">Ganti Author</button>

                        <button type="button"
                            @click="closeMenu(); $dispatch('open-status-modal', { routeKey: '{{ $item->getRouteKey() }}', currentStatus: '{{ $item->status }}' })"
                            class="w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">Ubah Status</button>
                    @endcan

                    <form action="{{ route('admin.wisata.destroy', $item) }}" method="POST"
                        onsubmit="return confirm('Yakin ingin menghapus artikel ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 text-sm text-left text-red-700 hover:bg-gray-100">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </td>
</tr>