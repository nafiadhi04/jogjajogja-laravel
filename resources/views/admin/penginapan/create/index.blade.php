{{-- resources/views/admin/penginapan/create/index.blade.php --}}
<x-app-layout>
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush

    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 overflow-hidden bg-white rounded-lg shadow">
                <h1 class="mb-4 text-lg font-semibold text-gray-800">Tambah Artikel Penginapan Baru</h1>
                {{-- form partial --}}
                @include('admin.penginapan.create._form')
            </div>
        </div>
    </div>

    {{-- scripts partial --}}
    @include('admin.penginapan.create._scripts')
</x-app-layout>