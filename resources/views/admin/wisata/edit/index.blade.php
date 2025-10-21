{{-- resources/views/admin/wisata/edit/index.blade.php --}}
<x-app-layout>
    {{-- Styles --}}
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush

    {{-- Form partial --}}
    @include('admin.wisata.edit._form')

    {{-- Scripts partial --}}
    @include('admin.wisata.edit._scripts')
</x-app-layout>