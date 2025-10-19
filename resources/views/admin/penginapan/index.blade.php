{{-- resources/views/admin/penginapan/index.blade.php --}}
<x-app-layout>
    {{-- Alpine helper untuk table state --}}
    <script>
        document.addEventListener('alpine:init', () => {
            Alpine.data('tableData', (itemsOnPage = 0) => ({
                selectedIds: [],
                selectAll: false,
                itemsOnPage: Number(itemsOnPage) || 0,
                toggleSelectAll() {
                    this.selectAll = !this.selectAll;
                    if (this.selectAll) {
                        this.selectedIds = Array.from(document.querySelectorAll('.item-checkbox')).map(cb => parseInt(cb.value));
                    } else {
                        this.selectedIds = [];
                    }
                },
                updateSelectAllState() {
                    this.selectedIds = Array.from(new Set(this.selectedIds.map(i => parseInt(i))));
                    this.selectAll = (this.itemsOnPage > 0) && (this.selectedIds.length === this.itemsOnPage);
                },
                submitMassDelete(formEl) {
                    const count = this.selectedIds.length;
                    if (count === 0) return;
                    if (confirm('Anda yakin ingin menghapus ' + count + ' artikel penginapan yang dipilih?')) {
                        formEl && formEl.submit();
                    }
                }
            }));
        });
    </script>

    <div class="py-6" x-data="tableData({{ $all_penginapan->count() ?? 0 }})">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">

            {{-- Route templates untuk membangun URL (replace __ID__ dengan routeKey) --}}
            <div id="route-templates"
                 data-penginapan-status-template="{{ url('admin/penginapan/__ID__/status') }}"
                 data-penginapan-author-template="{{ url('admin/penginapan/__ID__/author') }}"
                 class="hidden"></div>

            <div class="p-4 bg-white rounded-md shadow-sm">

                {{-- Header --}}
                <div class="flex flex-col gap-3 mb-4 md:flex-row md:items-center md:justify-between">
                    <h1 class="text-lg font-semibold text-gray-800">Kelola Artikel Penginapan</h1>

                    <div class="flex items-center gap-3">
                        <div x-show="selectedIds.length > 0" x-transition>
                            <form action="{{ route('admin.penginapan.destroy.multiple') }}" method="POST" @submit.prevent="submitMassDelete($el)">
                                @csrf
                                <template x-for="id in selectedIds" :key="id">
                                    <input type="hidden" name="ids[]" :value="id">
                                </template>
                                <button type="submit" class="px-3 py-1 text-xs font-semibold text-white bg-red-600 rounded hover:bg-red-700">
                                    Hapus (<span x-text="selectedIds.length"></span>)
                                </button>
                            </form>
                        </div>

                        <a href="{{ route('admin.penginapan.create') }}" class="px-3 py-1 text-xs font-semibold text-white bg-indigo-600 rounded hover:bg-indigo-700">
                            + Tambah Artikel
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
                                                                    positionMenuBound: null,
                                                                    positionMenu() {
                                                                        const btn = this.$refs.btn;
                                                                        if (!btn) return;
                                                                        const r = btn.getBoundingClientRect();
                                                                        const gap = 8;
                                                                        const MIN_W = 180;
                                                                        const CAP_W = Math.min(360, window.innerWidth - 24);
                                                                        const desiredW = Math.min(Math.max(r.width, MIN_W), CAP_W);

                                                                        // vertical position choose below/above (estimate height)
                                                                        const EST_H = 160;
                                                                        const belowSpace = window.innerHeight - r.bottom;
                                                                        const aboveSpace = r.top;
                                                                        let top;
                                                                        let computedMaxH = null;

                                                                        if (belowSpace >= EST_H + gap) {
                                                                            top = r.bottom + gap;
                                                                        } else if (aboveSpace >= EST_H + gap) {
                                                                            top = r.top - EST_H - gap;
                                                                        } else {
                                                                            if (belowSpace >= aboveSpace) {
                                                                                top = r.bottom + gap;
                                                                                computedMaxH = Math.max(belowSpace - gap, 80);
                                                                            } else {
                                                                                // place above and limit height by aboveSpace
                                                                                top = Math.max(gap, r.top - Math.max(aboveSpace - gap, 80) - gap);
                                                                                computedMaxH = Math.max(aboveSpace - gap, 80);
                                                                            }
                                                                        }

                                                                        let left = r.right - desiredW;
                                                                        left = Math.min(Math.max(left, 8), Math.max(window.innerWidth - desiredW - 8, 8));

                                                                        // assign style properties (string values)
                                                                        this.style = {
                                                                            position: 'fixed',
                                                                            top: Math.round(top) + 'px',
                                                                            left: Math.round(left) + 'px',
                                                                            width: Math.round(desiredW) + 'px',
                                                                            boxSizing: 'border-box',
                                                                            overflowX: 'hidden',
                                                                            whiteSpace: 'normal',
                                                                            wordBreak: 'break-word',
                                                                            maxHeight: computedMaxH ? (Math.round(computedMaxH) + 'px') : 'none',
                                                                            overflowY: computedMaxH ? 'auto' : 'visible'
                                                                        };
                                                                    },
                                                                    openMenu() {
                                                                        this.open = !this.open;
                                                                        if (this.open) {
                                                                            // bind once
                                                                            this.positionMenuBound = this.positionMenu.bind(this);
                                                                            this.positionMenu();
                                                                            window.addEventListener('resize', this.positionMenuBound);
                                                                            window.addEventListener('scroll', this.positionMenuBound, true);
                                                                            document.addEventListener('keydown', this._escapeHandler = (e) => { if (e.key === 'Escape') this.closeMenu(); });
                                                                        } else {
                                                                            this.closeMenu();
                                                                        }
                                                                    },
                                                                    closeMenu() {
                                                                        this.open = false;
                                                                        if (this.positionMenuBound) {
                                                                            window.removeEventListener('resize', this.positionMenuBound);
                                                                            window.removeEventListener('scroll', this.positionMenuBound, true);
                                                                            this.positionMenuBound = null;
                                                                        }
                                                                        if (this._escapeHandler) {
                                                                            document.removeEventListener('keydown', this._escapeHandler);
                                                                            this._escapeHandler = null;
                                                                        }
                                                                    }
                                                                }" @click.outside="closeMenu()">
                                                                    <button x-ref="btn" @click="openMenu()" type="button"
                                                                        class="inline-flex items-center px-2 py-1 text-xs text-gray-600 bg-gray-100 rounded hover:bg-gray-200">
                                                                        <span class="material-symbols-outlined !text-base mr-1">settings</span>
                                                                        <span>Pilihan</span>
                                                                    </button>

                                                                {{-- Dropdown menu (fixed positioned) --}}
                                                                <div x-show="open" x-cloak x-transition x-ref="menu" :style="style"
                                                                    class="z-50 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                                                                    style="min-width:180px; max-width:360px; overflow-x:hidden; word-break:break-word;">
                                                                    <div class="py-1">
                                                                        {{-- Edit: tampil untuk admin atau pemilik (owner) --}}
                                                                        @if(Auth::user()->role === 'admin' || $item->user_id === Auth::id())
                                                                            <a href="{{ route('admin.penginapan.edit', $item) }}"
                                                                                class="block px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">Edit</a>
                                                                        @endif

                                                                        {{-- Ganti Author & Ubah Status: hanya admin --}}
                                                                        @can('admin')
                                                                            <button type="button"
                                                                                @click="closeMenu(); $dispatch('open-author-modal', { routeKey: '{{ $item->getRouteKey() }}' })"
                                                                                class="w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">Ganti Author</button>

                                                                            <button type="button"
                                                                                @click="closeMenu(); $dispatch('open-status-modal', { routeKey: '{{ $item->getRouteKey() }}', currentStatus: '{{ $item->status }}' })"
                                                                                class="w-full px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">Ubah Status</button>
                                                                        @endcan

                                                                        <form action="{{ route('admin.penginapan.destroy', $item) }}" method="POST"
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

    {{-- Author Modal (server-side form based) --}}
    @can('admin')
        <div x-data="{ open:false, routeKey:null, selectedAuthor:null }"
             x-on:open-author-modal.window="open = true; routeKey = $event.detail.routeKey; selectedAuthor = null"
             x-cloak>
            <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div x-show="open" x-transition class="fixed inset-0 bg-gray-500 bg-opacity-60"></div>

                <div x-show="open" x-transition @click.outside="open = false" class="relative w-full max-w-lg bg-white rounded-md shadow-lg">
                    <form method="POST" :action="(() => {
                        const tpl = document.getElementById('route-templates')?.dataset?.penginapanAuthorTemplate || '/admin/penginapan/__ID__/author';
                        return tpl.replace('__ID__', encodeURIComponent(routeKey || ''));
                    })()">
                        @csrf
                        @method('PATCH')
                        <div class="p-4">
                            <h3 class="text-base font-medium text-gray-900">Ganti Author</h3>
                            <div class="mt-3">
                                <label class="block text-xs font-medium text-gray-700">Pilih Author</label>
                                <select x-model="selectedAuthor" name="user_id" class="block w-full mt-1 text-sm border-gray-300 rounded">
                                    <option value="">-- Pilih Author --</option>
                                    @foreach($authors as $a)
                                        <option value="{{ $a['id'] }}">{{ $a['name'] }} ({{ $a['role'] ?? '-' }})</option>
                                    @endforeach
                                </select>
                            </div>
                        </div>

                        <div class="flex justify-end px-4 py-3 space-x-2 bg-gray-50">
                            <button type="button" @click="open=false" class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                            <button :disabled="!selectedAuthor" type="submit" class="px-3 py-1 text-xs font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700">Simpan</button>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    @endcan

    {{-- Status Modal (AJAX PATCH) --}}
    @can('admin')
        <div x-data="{
                open:false,
                routeKey:null,
                status:null,
                loading:false,
                error:null,
                openFor(event){
                    console.log('[status-modal] openFor', event.detail);
                    // accept routeKey or legacy id
                    this.routeKey = event.detail?.routeKey ?? event.detail?.id ?? null;
                    this.status = event.detail?.currentStatus ?? event.detail?.status ?? null;
                    this.error = null;
                    this.loading = false;
                    setTimeout(()=> this.open = true, 10);
                },
                buildUrl(){
                    const tpl = document.getElementById('route-templates')?.dataset?.penginapanStatusTemplate || '/admin/penginapan/__ID__/status';
                    return tpl.replace('__ID__', encodeURIComponent(this.routeKey || ''));
                },
                async submitStatus(){
                    console.log('[status-modal] submitStatus start', { routeKey: this.routeKey, status: this.status });
                    if (this.loading) {
                        console.log('[status-modal] already loading');
                        return;
                    }
                    if (!this.routeKey) {
                        this.error = 'Identifier item tidak tersedia.';
                        return;
                    }
                    if (!this.status) {
                        this.error = 'Pilih Status baru.';
                        return;
                    }

                    this.loading = true;
                    this.error = null;

                    const token = document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '';
                    const url = this.buildUrl();
                    const payload = { status: this.status };
                    if (this.status === 'revisi') payload.catatan_revisi = this.$refs.catatan ? this.$refs.catatan.value : '';

                    try {
                        console.log('[status-modal] fetch PATCH', url, payload);
                        const res = await fetch(url, {
                            method: 'PATCH',
                            credentials: 'same-origin',
                            headers: {
                                'Content-Type': 'application/json',
                                'X-CSRF-TOKEN': token,
                                'X-Requested-With': 'XMLHttpRequest'
                            },
                            body: JSON.stringify(payload)
                        });

                        const ct = res.headers.get('content-type') || '';
                        let body = null;
                        if (ct.includes('application/json')) body = await res.json();
                        else body = await res.text();

                        console.log('[status-modal] fetch done', res.status, body);

                        if (!res.ok) {
                            if (body && body.message) this.error = body.message + (body.error ? ' — ' + body.error : '');
                            else if (typeof body === 'string') this.error = body;
                            else this.error = `Gagal (HTTP ${res.status}). Lihat console.`;
                            this.loading = false;
                            return;
                        }

                        this.loading = false;
                        this.open = false;
                        window.location.reload();
                    } catch (err) {
                        console.error('[status-modal] fetch error', err);
                        this.error = 'Terjadi error jaringan. Lihat console.';
                        this.loading = false;
                    }
                }
            }"
             x-on:open-status-modal.window="openFor($event)"
             x-cloak>
            <div x-show="open" x-transition.opacity class="fixed inset-0 z-40 bg-black/40"></div>

            <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div @click.stop class="relative w-full max-w-md">
                    <div class="overflow-hidden bg-white rounded-lg shadow-lg">
                        <div class="p-4">
                            <h3 class="text-base font-medium text-gray-900">Ubah Status</h3>

                            <div class="mt-3 space-y-3">
                                <div>
                                    <label class="block text-xs font-medium text-gray-700">Status Baru</label>
                                    <select x-model="status" class="block w-full mt-1 text-sm border-gray-300 rounded" required>
                                        <option value="">-- pilih status --</option>
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

                        <div class="flex justify-end gap-2 px-4 py-3 bg-gray-50">
                            <button type="button" @click="open=false" class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                            <button type="button" @click="submitStatus()" :disabled="loading" class="px-3 py-1 text-xs font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700">
                                <span x-show="!loading">Simpan</span>
                                <span x-show="loading">Menyimpan...</span>
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    @endcan

</x-app-layout>
