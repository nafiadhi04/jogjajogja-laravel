<section x-data="cropperManager()">
    <header>
        <h2 class="text-lg font-medium text-gray-900">
            {{ __('Informasi Profil') }}
        </h2>
        <p class="mt-1 text-sm text-gray-600">
            {{ __("Perbarui informasi profil dan alamat email akun Anda.") }}
        </p>
    </header>

    <form id="send-verification" method="post" action="{{ route('verification.send') }}">
        @csrf
    </form>

    <form method="post" action="{{ route('profile.update') }}" class="mt-6 space-y-6">
        @csrf
        @method('patch')

        {{-- Bagian Upload Foto Profil --}}
        <div>
            <label class="block text-sm font-medium text-gray-700">Foto Profil</label>
            <div class="flex items-center mt-2 gap-x-4">
                <img x-ref="preview"
                    src="{{ $user->profile_photo_path ? asset('storage/' . $user->profile_photo_path) : 'https://ui-avatars.com/api/?name=' . urlencode($user->name) . '&color=7F9CF5&background=EBF4FF' }}"
                    alt="Current profile photo" class="object-cover w-24 h-24 rounded-full">
                <div>
                    <input type="file" @change="handleFileSelect" accept="image/*" class="hidden" x-ref="fileInput">
                    <button type="button" @click="$refs.fileInput.click()"
                        class="px-3 py-2 text-sm font-semibold text-gray-900 bg-white rounded-md shadow-sm ring-1 ring-inset ring-gray-300 hover:bg-gray-50">
                        Ganti Foto
                    </button>
                    <p class="mt-1 text-xs text-gray-500">PNG, JPG, GIF hingga 2MB.</p>
                </div>
            </div>
            <input type="hidden" name="profile_photo" x-ref="photoInput">
        </div>

        {{-- Nama --}}
        <div>
            <x-input-label for="name" :value="__('Nama')" />
            <x-text-input id="name" name="name" type="text" class="block w-full mt-1" :value="old('name', $user->name)"
                required autofocus autocomplete="name" />
            <x-input-error class="mt-2" :messages="$errors->get('name')" />
        </div>

        {{-- Email --}}
        <div>
            <x-input-label for="email" :value="__('Email')" />
            <x-text-input id="email" name="email" type="email" class="block w-full mt-1" :value="old('email', $user->email)" required autocomplete="username" />
            <x-input-error class="mt-2" :messages="$errors->get('email')" />

            @if ($user instanceof \Illuminate\Contracts\Auth\MustVerifyEmail && !$user->hasVerifiedEmail())
                <div>
                    <p class="mt-2 text-sm text-gray-800">
                        {{ __('Alamat email Anda belum terverifikasi.') }}
                        <button form="send-verification"
                            class="text-sm text-gray-600 underline rounded-md hover:text-gray-900 focus:outline-none focus:ring-2 focus:ring-offset-2 focus:ring-indigo-500">
                            {{ __('Klik di sini untuk mengirim ulang email verifikasi.') }}
                        </button>
                    </p>
                    @if (session('status') === 'verification-link-sent')
                        <p class="mt-2 text-sm font-medium text-green-600">
                            {{ __('Tautan verifikasi baru telah dikirim ke alamat email Anda.') }}
                        </p>
                    @endif
                </div>
            @endif
        </div>

        {{-- Tombol Simpan --}}
        <div class="flex items-center gap-4">
            <x-primary-button>{{ __('Simpan') }}</x-primary-button>
            @if (session('status') === 'profile-updated')
                <p x-data="{ show: true }" x-show="show" x-transition x-init="setTimeout(() => show = false, 2000)"
                    class="text-sm text-gray-600">{{ __('Tersimpan.') }}</p>
            @endif
        </div>
    </form>

    {{-- Modal untuk Cropping --}}
    <div x-show="modalOpen" @keydown.escape.window="modalOpen = false" class="fixed inset-0 z-50 overflow-y-auto"
        x-cloak>
        <div class="flex items-center justify-center min-h-screen px-4 text-center">
            <div x-show="modalOpen" x-transition class="fixed inset-0 transition-opacity bg-gray-500 bg-opacity-75">
            </div>
            <div x-show="modalOpen" x-transition
                class="inline-block w-full max-w-lg p-6 my-8 overflow-hidden text-left align-middle transition-all transform bg-white rounded-lg shadow-xl">
                <div>
                    <h3 class="text-lg font-medium leading-6 text-gray-900">Potong Gambar</h3>
                    <div class="mt-2">
                        <div class="img-container max-h-[60vh]">
                            <img x-ref="imageToCrop" src="" alt="Image to crop">
                        </div>
                    </div>
                </div>
                <div class="mt-5 sm:mt-6 sm:flex sm:flex-row-reverse">
                    <button type="button" @click="cropImage"
                        class="inline-flex justify-center w-full px-4 py-2 text-base font-medium text-white bg-indigo-600 border border-transparent rounded-md shadow-sm hover:bg-indigo-700 sm:ml-3 sm:w-auto sm:text-sm">
                        Potong & Gunakan
                    </button>
                    <button type="button" @click="closeModal"
                        class="inline-flex justify-center w-full px-4 py-2 mt-3 text-base font-medium text-gray-700 bg-white border border-gray-300 rounded-md shadow-sm hover:bg-gray-50 sm:mt-0 sm:w-auto sm:text-sm">
                        Batal
                    </button>
                </div>
            </div>
        </div>
    </div>
</section>

{{-- Script Alpine.js untuk mengelola Cropper --}}
<script>
    function cropperManager() {
        return {
            modalOpen: false,
            cropper: null,
            handleFileSelect(event) {
                const file = event.target.files[0];
                if (file) {
                    const reader = new FileReader();
                    reader.onload = (e) => {
                        this.$refs.imageToCrop.src = e.target.result;
                        this.modalOpen = true;
                        this.$nextTick(() => {
                            this.initCropper();
                        });
                    };
                    reader.readAsDataURL(file);
                }
            },
            initCropper() {
                if (this.cropper) {
                    this.cropper.destroy();
                }
                this.cropper = new Cropper(this.$refs.imageToCrop, {
                    aspectRatio: 1,
                    viewMode: 1,
                    dragMode: 'move',
                    background: false,
                });
            },
            cropImage() {
                const canvas = this.cropper.getCroppedCanvas({
                    width: 400,
                    height: 400,
                });
                this.$refs.preview.src = canvas.toDataURL();
                this.$refs.photoInput.value = canvas.toDataURL('image/png');
                this.closeModal();
            },
            closeModal() {
                this.modalOpen = false;
                if (this.cropper) {
                    this.cropper.destroy();
                    this.cropper = null;
                }
            }
        }
    }
</script>