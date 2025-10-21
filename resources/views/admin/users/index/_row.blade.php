{{-- resources/views/admin/users/index/_row.blade.php --}}
<tr class="hover:bg-gray-50" x-data="{ roleModalOpen: false }">
    {{-- Checkbox --}}
    <td class="px-3 py-2 text-center align-middle border">
        <input type="checkbox" value="{{ $user->id }}" x-model="selectedIds" @change="updateSelectAllState"
            class="rounded item-checkbox">
    </td>

    {{-- No --}}
    <td class="px-3 py-2 text-center align-middle border">
        {{ ($users->currentPage() - 1) * $users->perPage() + $loop->iteration }}
    </td>

    {{-- Name + Email --}}
    <td class="px-3 py-2 align-top border">
        <div class="flex items-center gap-3">
            <img src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}"
                alt="{{ $user->name }}" class="object-cover w-8 h-8 rounded-full ring-1 ring-slate-200">
            <div class="min-w-0">
                <div class="text-sm font-medium text-gray-800 truncate">{{ $user->name }}</div>
                <div class="text-xs text-gray-500 truncate">{{ $user->email }}</div>
            </div>
        </div>
    </td>

    {{-- Role badge --}}
    <td class="px-3 py-2 align-top border">
        @php
            $roleClass = match ($user->role) {
                'admin' => 'bg-indigo-100 text-indigo-800',
                'platinum' => 'bg-gray-800 text-gray-100',
                'gold' => 'bg-yellow-100 text-yellow-800',
                'silver' => 'bg-gray-200 text-gray-800',
                'pending' => 'bg-red-100 text-red-800',
                default => 'bg-green-100 text-green-800',
            };
        @endphp

        @can('admin')
            <button @click="roleModalOpen = true"
                class="inline-flex items-center gap-2 px-2 py-0.5 text-xs font-semibold rounded-full {{ $roleClass }}">
                {{ ucfirst($user->role) }}
            </button>
        @else
            <span class="inline-block px-2 py-0.5 text-xs font-semibold rounded-full {{ $roleClass }}">
                {{ ucfirst($user->role) }}
            </span>
        @endcan
    </td>

    {{-- Actions dropdown --}}
    <td class="px-3 py-2 align-top border">
        <div x-data="{
                open: false,
                style: {},
                positionMenu() {
                    const btn = this.$refs.btn;
                    if (!btn) return;
                    const r = btn.getBoundingClientRect();
                    const gap = 8;
                    const MIN_W = 160;
                    const CAP_W = Math.min(320, window.innerWidth - 24);
                    const desiredW = Math.min(Math.max(r.width, MIN_W), CAP_W);

                    const EST_H = 120;
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
                            top = Math.max(gap, r.top - Math.max(aboveSpace - gap, 80) - gap);
                            computedMaxH = Math.max(aboveSpace - gap, 80);
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
                        wordBreak: 'break-word',
                        maxHeight: computedMaxH ? (Math.round(computedMaxH) + 'px') : 'none',
                        overflowY: computedMaxH ? 'auto' : 'visible'
                    };
                },
                openMenu() {
                    this.open = !this.open;
                    if (this.open) {
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

            <div x-show="open" x-cloak x-transition :style="style"
                class="z-50 bg-white rounded-md shadow-lg ring-1 ring-black ring-opacity-5"
                style="min-width:160px; max-width:320px; overflow-x:hidden;">
                <div class="py-1">
                    <a href="{{ route('admin.users.edit', $user->id) }}"
                        class="block px-4 py-2 text-sm text-left text-gray-700 hover:bg-gray-100">Edit</a>

                    <form action="{{ route('admin.users.destroy', $user->id) }}" method="POST"
                        onsubmit="return confirm('Apakah Anda yakin ingin menghapus user ini?');">
                        @csrf
                        @method('DELETE')
                        <button type="submit"
                            class="w-full px-4 py-2 text-sm text-left text-red-600 hover:bg-gray-100">Hapus</button>
                    </form>
                </div>
            </div>
        </div>

        {{-- Role Modal (admin only) --}}
        @can('admin')
            <div x-show="roleModalOpen" x-cloak class="fixed inset-0 z-50 flex items-center justify-center p-4">
                <div x-show="roleModalOpen" x-transition class="fixed inset-0 bg-gray-500 bg-opacity-75"></div>
                <div x-show="roleModalOpen" x-transition @click.outside="roleModalOpen = false"
                    class="relative w-full max-w-md bg-white rounded-lg shadow-xl">
                    <form action="{{ route('admin.users.update', $user) }}" method="POST" class="space-y-4">
                        @csrf
                        @method('PUT')
                        <div class="p-6">
                            <h3 class="text-lg font-semibold text-gray-900">Ubah Peran untuk: {{ $user->name }}</h3>
                            <input type="hidden" name="name" value="{{ $user->name }}">
                            <input type="hidden" name="email" value="{{ $user->email }}">
                            <div class="mt-4">
                                <label for="role_{{ $user->id }}" class="block text-sm font-medium text-gray-700">Peran
                                    Baru</label>
                                <select name="role" id="role_{{ $user->id }}"
                                    class="block w-full mt-1 text-sm border-gray-300 rounded">
                                    <option value="pending" @selected($user->role == 'pending')>Pending</option>
                                    <option value="silver" @selected($user->role == 'silver')>Silver</option>
                                    <option value="gold" @selected($user->role == 'gold')>Gold</option>
                                    <option value="platinum" @selected($user->role == 'platinum')>Platinum</option>
                                    <option value="member" @selected($user->role == 'member')>Member</option>
                                    <option value="admin" @selected($user->role == 'admin')>Admin</option>
                                </select>
                            </div>
                        </div>
                        <div class="flex justify-end px-6 py-4 space-x-3 bg-gray-50">
                            <button type="button" @click="roleModalOpen = false"
                                class="px-4 py-2 text-sm font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                            <button type="submit"
                                class="px-4 py-2 text-sm font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700">Simpan
                                Peran</button>
                        </div>
                    </form>
                </div>
            </div>
        @endcan
    </td>
</tr>