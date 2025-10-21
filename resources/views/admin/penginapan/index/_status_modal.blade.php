{{-- resources/views/admin/penginapan/_status_modal.blade.php --}}


<div x-data="{
        open:false,
        routeKey:null,
        status:null,
        loading:false,
        error:null,
        openFor(event){
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
            if (this.loading) return;
            if (!this.routeKey) { this.error = 'Identifier item tidak tersedia.'; return; }
            if (!this.status) { this.error = 'Pilih Status baru.'; return; }

            this.loading = true;
            this.error = null;

            const token = document.querySelector('meta[name=csrf-token]')?.getAttribute('content') || '';
            const url = this.buildUrl();
            const payload = { status: this.status };
            if (this.status === 'revisi') payload.catatan_revisi = this.$refs.catatan ? this.$refs.catatan.value : '';

            try {
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

                if (!res.ok) {
                    if (body && body.message) this.error = body.message;
                    else if (typeof body === 'string') this.error = body;
                    else this.error = `Gagal (HTTP ${res.status}). Lihat console.`;
                    this.loading = false;
                    return;
                }

                this.loading = false;
                this.open = false;
                window.location.reload();
            } catch (err) {
                console.error(err);
                this.error = 'Terjadi error jaringan. Lihat console.';
                this.loading = false;
            }
        }
    }" x-on:open-status-modal.window="openFor($event)" x-cloak>
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
                            <textarea x-ref="catatan" rows="3"
                                class="block w-full mt-1 text-sm border-gray-300 rounded"></textarea>
                        </div>

                        <template x-if="error">
                            <div class="p-2 text-sm text-red-700 bg-red-100 rounded" x-text="error"></div>
                        </template>
                    </div>
                </div>

                <div class="flex justify-end gap-2 px-4 py-3 bg-gray-50">
                    <button type="button" @click="open=false"
                        class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                    <button type="button" @click="submitStatus()" :disabled="loading"
                        class="px-3 py-1 text-xs font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700">
                        <span x-show="!loading">Simpan</span>
                        <span x-show="loading">Menyimpan...</span>
                    </button>
                </div>
            </div>
        </div>
    </div>
</div>