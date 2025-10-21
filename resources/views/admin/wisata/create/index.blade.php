{{-- resources/views/admin/wisata/create/index.blade.php --}}
<x-app-layout>
    {{-- Styles (tetap di-push supaya layout menerima) --}}
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush

    {{-- Form utama (partial) --}}
    @include('admin.wisata.create._form')

    {{-- Scripts (partial) --}}
    @include('admin.wisata.create._scripts')
</x-app-layout>