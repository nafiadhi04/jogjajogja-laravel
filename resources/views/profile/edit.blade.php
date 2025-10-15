<x-app-layout>
    {{-- ========================================================== --}}
    {{-- ASSET UNTUK CROPPER.JS (bila ada upload foto profil) --}}
    {{-- ========================================================== --}}
    @push('styles')
        <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.css" />
    @endpush

    @push('scripts')
        <script src="https://cdnjs.cloudflare.com/ajax/libs/cropperjs/1.6.1/cropper.min.js"></script>

        {{-- Alpine component untuk cropper --}}
        <script>
            function cropperManager() {
                return {
                    modalOpen: false,
                    cropper: null,
                    objectUrl: null,

                    // dipakai untuk preview awal (dipasok dari server)
                    previewSrc: `{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}`,

                    handleFileSelect(event) {
                        const file = event.target.files[0];
                        if (!file) return;

                        // Validasi tipe
                        if (!file.type.startsWith('image/')) {
                            alert('Silakan pilih file gambar (PNG/JPG/GIF).');
                            event.target.value = null;
                            return;
                        }

                        // Validasi ukuran (2MB)
                        const maxBytes = 2 * 1024 * 1024;
                        if (file.size > maxBytes) {
                            alert('Ukuran file terlalu besar. Maksimum 2MB.');
                            event.target.value = null;
                            return;
                        }

                        // Jika sebelumnya ada objectUrl, revoke
                        if (this.objectUrl) {
                            URL.revokeObjectURL(this.objectUrl);
                            this.objectUrl = null;
                        }

                        // Gunakan object URL untuk performa (lebih cepat dari FileReader)
                        this.objectUrl = URL.createObjectURL(file);
                        // set src image untuk cropper
                        this.$refs.imageToCrop.src = this.objectUrl;

                        // buka modal dan inisialisasi cropper setelah elemen gambar ada
                        this.modalOpen = true;
                        this.$nextTick(() => this.initCropper());
                    },

                    initCropper() {
                        if (!this.$refs.imageToCrop) return;

                        // destroy cropper bila sudah ada
                        if (this.cropper) {
                            try { this.cropper.destroy(); } catch (e) { /* ignore */ }
                            this.cropper = null;
                        }

                        // instantiate cropper
                        this.cropper = new Cropper(this.$refs.imageToCrop, {
                            aspectRatio: 1,
                            viewMode: 1,
                            autoCropArea: 0.9,
                            background: false,
                            responsive: true,
                            movable: true,
                            zoomable: true,
                            scalable: false,
                            rotatable: false,
                        });
                    },

                    cropImage() {
                        if (!this.cropper) return;

                        // getCroppedCanvas — output 400x400 (ubah jika perlu)
                        const canvas = this.cropper.getCroppedCanvas({
                            width: 400,
                            height: 400,
                            imageSmoothingQuality: 'high'
                        });

                        // langsung dapatkan dataURL
                        const dataUrl = canvas.toDataURL('image/png', 0.9);

                        // isi hidden input untuk dikirim ke backend
                        if (this.$refs.photoInput) {
                            this.$refs.photoInput.value = dataUrl;
                        }

                        // update preview
                        if (this.$refs.preview) {
                            this.$refs.preview.src = dataUrl;
                        } else {
                            this.previewSrc = dataUrl;
                        }

                        // cleanup & tutup modal
                        this.closeModal();
                    },

                    cancelCrop() {
                        // kembalikan file input
                        if (this.$refs.fileInput) {
                            this.$refs.fileInput.value = null;
                        }
                        // revoke object url jika ada
                        if (this.objectUrl) {
                            URL.revokeObjectURL(this.objectUrl);
                            this.objectUrl = null;
                        }
                        // destroy cropper
                        if (this.cropper) {
                            try { this.cropper.destroy(); } catch (e) { }
                            this.cropper = null;
                        }
                        this.modalOpen = false;
                    },

                    closeModal() {
                        // destroy cropper dan tutup modal
                        if (this.cropper) {
                            try { this.cropper.destroy(); } catch (e) { }
                            this.cropper = null;
                        }
                        if (this.objectUrl) {
                            URL.revokeObjectURL(this.objectUrl);
                            this.objectUrl = null;
                        }
                        this.modalOpen = false;
                    }
                }
            }
        </script>
    @endpush

    <x-slot name="header">
        <h2 class="text-xl font-semibold leading-tight text-gray-800">
            {{ __('Edit Profile') }}
        </h2>
    </x-slot>

    <div class="py-12 bg-gray-100">
        <div class="max-w-5xl mx-auto space-y-8 sm:px-6 lg:px-8">
            {{-- ==================== INFORMASI PROFIL ==================== --}}
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Informasi Profil</h3>
                <form method="post" action="{{ route('profile.update') }}" class="space-y-6">
                    @csrf
                    @method('patch')

                    {{-- Bagian Upload Foto Profil (x-data lokal untuk cropper) --}}
                    <div x-data="cropperManager()" x-cloak>
                        <label class="block text-sm font-medium text-gray-700">Foto Profil</label>

                        <div class="flex items-center mt-2 gap-x-4">
                            {{-- Preview Saat Ini --}}
                            <img x-ref="preview" :src="previewSrc" alt="Current profile photo"
                                class="object-cover w-24 h-24 border border-gray-200 rounded-full">

                            <div>
                                <input type="file" accept="image/*" x-ref="fileInput" @change="handleFileSelect($event)"
                                    class="hidden">
                                <button type="button" @click="$refs.fileInput.click()"
                                    class="px-3 py-2 text-sm font-semibold text-gray-900 bg-white rounded-md shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                                    Ganti Foto
                                </button>
                                <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF hingga 2MB.</p>
                            </div>
                        </div>

                        {{-- Hidden input berisi base64 hasil crop (backend harus siap menerima base64) --}}
                        <input type="hidden" name="profile_photo" x-ref="photoInput">

                        {{-- Modal Cropper --}}
                        <div x-show="modalOpen" x-cloak
                            class="fixed inset-0 z-50 flex items-center justify-center p-4 bg-black/50">
                            <div @keydown.window.escape="closeModal()"
                                class="w-full max-w-3xl bg-white rounded shadow-lg">
                                <div class="flex items-center justify-between px-4 py-2 border-b">
                                    <h3 class="text-sm font-semibold text-gray-800">Potong Foto Profil</h3>
                                    <button type="button" @click="closeModal()"
                                        class="text-gray-600 hover:text-gray-800">&times;</button>
                                </div>

                                <div class="p-4">
                                    <div class="w-full h-[420px] flex items-center justify-center">
                                        {{-- Elemen gambar untuk cropper --}}
                                        <img x-ref="imageToCrop" alt="To crop" class="max-h-[400px] block">
                                    </div>
                                </div>

                                <div class="flex items-center justify-end gap-3 px-4 py-3 border-t">
                                    <button type="button" @click="cancelCrop()"
                                        class="px-3 py-1 text-sm font-medium bg-white border rounded hover:bg-gray-50">
                                        Batal
                                    </button>
                                    <button type="button" @click="cropImage()"
                                        class="px-3 py-1 text-sm font-medium text-white bg-indigo-600 rounded hover:bg-indigo-700">
                                        Simpan Foto
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    {{-- END Bagian Upload Foto Profil --}}

                    {{-- Nama --}}
                    <div>
                        <label for="name" class="block text-sm font-medium text-gray-700">Nama</label>
                        <input id="name" name="name" type="text" value="{{ old('name', $user->name) }}"
                            class="block w-full mt-1 text-gray-900 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required autocomplete="name">
                        @error('name')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Email --}}
                    <div>
                        <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                        <input id="email" name="email" type="email" value="{{ old('email', $user->email) }}"
                            class="block w-full mt-1 text-gray-900 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            required autocomplete="username">
                        @error('email')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Simpan</x-primary-button>

                        @if (session('status') === 'profile-updated')
                            <p x-data="{ show: true }" x-show="show" x-transition
                                x-init="setTimeout(() => show = false, 2000)" class="text-sm text-gray-600">
                                {{ __('Tersimpan.') }}
                            </p>
                        @endif
                    </div>
                </form>
            </div>

            {{-- ==================== GANTI PASSWORD ==================== --}}
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-gray-800">Ganti Password</h3>
                <form method="post" action="{{ route('password.update') }}" class="space-y-6">
                    @csrf
                    @method('put')

                    {{-- Password Sekarang --}}
                    <div>
                        <label for="current_password" class="block text-sm font-medium text-gray-700">Password
                            Sekarang</label>
                        <input id="current_password" name="current_password" type="password"
                            class="block w-full mt-1 text-gray-900 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            autocomplete="current-password">
                        @error('current_password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Password Baru --}}
                    <div>
                        <label for="password" class="block text-sm font-medium text-gray-700">Password Baru</label>
                        <input id="password" name="password" type="password"
                            class="block w-full mt-1 text-gray-900 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            autocomplete="new-password">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    {{-- Konfirmasi Password --}}
                    <div>
                        <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Konfirmasi
                            Password</label>
                        <input id="password_confirmation" name="password_confirmation" type="password"
                            class="block w-full mt-1 text-gray-900 bg-white border border-gray-300 rounded-md shadow-sm focus:border-indigo-500 focus:ring focus:ring-indigo-200 focus:ring-opacity-50"
                            autocomplete="new-password">
                        @error('password_confirmation')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <div class="flex items-center gap-4">
                        <x-primary-button>Perbarui Password</x-primary-button>
                    </div>
                </form>
            </div>

            {{-- ==================== HAPUS AKUN ==================== --}}
            <div class="p-6 bg-white border border-gray-200 rounded-lg shadow-sm">
                <h3 class="mb-4 text-lg font-semibold text-red-600">Hapus Akun</h3>
                <p class="mb-4 text-sm text-gray-600">
                    Setelah akun dihapus, semua data akan dihapus secara permanen. Tindakan ini tidak dapat dibatalkan.
                </p>
                <form method="post" action="{{ route('profile.destroy') }}" class="space-y-6">
                    @csrf
                    @method('delete')

                    <div>
                        <label for="password_delete" class="block text-sm font-medium text-gray-700">Masukkan Password
                            untuk Konfirmasi</label>
                        <input id="password_delete" name="password" type="password"
                            class="block w-full mt-1 text-gray-900 bg-white border border-gray-300 rounded-md shadow-sm focus:border-red-500 focus:ring focus:ring-red-200 focus:ring-opacity-50"
                            autocomplete="current-password">
                        @error('password')
                            <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                        @enderror
                    </div>

                    <x-danger-button>Hapus Akun</x-danger-button>
                </form>
            </div>
        </div>
    </div>
</x-app-layout>