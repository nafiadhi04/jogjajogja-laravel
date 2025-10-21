{{-- Wrapper utama untuk halaman Edit Penginapan --}}
<x-app-layout>
    @push('styles')
        <link href="https://cdn.quilljs.com/1.3.6/quill.snow.css" rel="stylesheet">
    @endpush
    <div class="py-8">
        <div class="mx-auto max-w-7xl sm:px-6 lg:px-8">
            <div class="p-4 overflow-hidden bg-white rounded-lg shadow">
                <h1 class="mb-4 text-lg font-semibold text-gray-800">Edit Artikel: {{ $penginapan->nama }}</h1>

                {{-- Errors dan pesan sukses gambar --}}
                @if ($errors->any())
                    <div class="p-3 mb-4 text-sm text-red-700 bg-red-100 rounded" role="alert">
                        <span class="font-semibold">Whoops! Terjadi kesalahan pada input Anda:</span>
                        <ul class="mt-2 list-disc list-inside">
                            @foreach ($errors->all() as $error)
                                <li class="text-xs">{{ $error }}</li>
                            @endforeach
                        </ul>
                    </div>
                @endif

                @if(session('success_gambar'))
                    <div class="p-3 mb-4 text-sm text-green-700 bg-green-100 rounded" role="alert">
                        {{ session('success_gambar') }}
                    </div>
                @endif

                {{-- Form partial --}}
                @include('admin.penginapan.edit._form', ['penginapan' => $penginapan, 'fasilitas' => $fasilitas])
            </div>
        </div>
    </div>

    {{-- Scripts (Quill dan Alpine helpers) --}}
    @include('admin.penginapan.edit._scripts')
</x-app-layout>