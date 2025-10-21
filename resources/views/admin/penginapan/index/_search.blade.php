{{-- resources/views/admin/penginapan/_search.blade.php --}}


<div class="p-3 mb-4 rounded bg-gray-50">
    <form action="{{ route('admin.penginapan.index') }}" method="GET" class="grid grid-cols-1 gap-3 md:grid-cols-4">
        <div class="md:col-span-3">
            <label for="search" class="text-xs font-medium text-gray-700">Cari Artikel</label>
            <input type="text" name="search" id="search" value="{{ request('search') }}"
                class="block w-full mt-1 text-sm border-gray-300 rounded shadow-sm"
                placeholder="Ketik nama, kota, tipe, status, atau author...">
        </div>
        <div class="flex items-end gap-2">
            <button type="submit"
                class="w-full px-3 py-1 text-xs font-semibold text-white bg-blue-600 rounded hover:bg-blue-700">Cari</button>
            <a href="{{ route('admin.penginapan.index') }}"
                class="w-full px-3 py-1 text-xs font-semibold text-center text-gray-700 bg-gray-200 rounded hover:bg-gray-300">Reset</a>
        </div>
    </form>
</div>