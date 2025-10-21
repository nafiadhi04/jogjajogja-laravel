{{-- resources/views/admin/penginapan/_author_modal.blade.php --}}
@props(['authors'])



<div x-data="{ open:false, routeKey:null, selectedAuthor:null }"
    x-on:open-author-modal.window="open = true; routeKey = $event.detail.routeKey; selectedAuthor = null" x-cloak>
    <div x-show="open" x-transition class="fixed inset-0 z-50 flex items-center justify-center p-4">
        <div x-show="open" x-transition class="fixed inset-0 bg-gray-500 bg-opacity-60"></div>

        <div x-show="open" x-transition @click.outside="open = false"
            class="relative w-full max-w-lg bg-white rounded-md shadow-lg">
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
                        <select x-model="selectedAuthor" name="user_id"
                            class="block w-full mt-1 text-sm border-gray-300 rounded">
                            <option value="">-- Pilih Author --</option>
                            @foreach($authors as $a)
                                <option value="{{ $a['id'] }}">{{ $a['name'] }} ({{ $a['role'] ?? '-' }})</option>
                            @endforeach
                        </select>
                    </div>
                </div>

                <div class="flex justify-end px-4 py-3 space-x-2 bg-gray-50">
                    <button type="button" @click="open=false"
                        class="px-3 py-1 text-xs font-medium text-gray-700 bg-white border border-gray-300 rounded hover:bg-gray-50">Batal</button>
                    <button :disabled="!selectedAuthor" type="submit"
                        class="px-3 py-1 text-xs font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700">Simpan</button>
                </div>
            </form>
        </div>
    </div>
</div>