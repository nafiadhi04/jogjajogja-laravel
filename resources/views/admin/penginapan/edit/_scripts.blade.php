@push('scripts')
    <script src="https://cdn.quilljs.com/1.3.6/quill.js"></script>

    <script>
        function formManager() {
            return {
                galleryFiles: [],
                galleryPreviews: [],
                triggerFileInput() { this.$refs.galleryInput.click(); },
                handleFileSelection(event) {
                    for (let i = 0; i < event.target.files.length; i++) {
                        const file = event.target.files[i];
                        this.galleryFiles.push(file);
                        const reader = new FileReader();
                        reader.onload = (e) => { this.galleryPreviews.push(e.target.result); };
                        reader.readAsDataURL(file);
                    }
                    event.target.value = null;
                },
                removeStagedFile(index) {
                    this.galleryFiles.splice(index, 1);
                    this.galleryPreviews.splice(index, 1);
                },
                prepareFormSubmit() {
                    const dataTransfer = new DataTransfer();
                    this.galleryFiles.forEach(file => { dataTransfer.items.add(file); });
                    this.$refs.galleryInput.files = dataTransfer.files;
                }
            }
        }

        document.addEventListener('DOMContentLoaded', function () {
            // Quill editor
            var quill = new Quill('#editor-container', {
                theme: 'snow',
                modules: {
                    toolbar: [
                        [{ 'header': [1, 2, 3, false] }],
                        ['bold', 'italic', 'underline'],
                        [{ 'list': 'ordered' }, { 'list': 'bullet' }],
                        [{ 'align': [] }],
                        ['link', 'image'],
                        ['clean']
                    ]
                }
            });

            var deskripsiInput = document.querySelector('#deskripsi-input');
            var form = document.querySelector('form');
            var charCount = document.querySelector('#char-count');

            // initial sync
            deskripsiInput.value = quill.root.innerHTML;
            if (charCount) {
                charCount.textContent = quill.getText().trim().length + ' / 5000';
            }

            quill.on('text-change', function () {
                let text = quill.root.innerHTML;
                let length = text.length;
                deskripsiInput.value = text;
                if (charCount) {
                    charCount.textContent = length + ' / 5000';
                    if (length > 5000) { charCount.classList.add('text-red-500'); } else { charCount.classList.remove('text-red-500'); }
                }
            });

            form.addEventListener('submit', function (e) {
                if (quill.root.innerHTML === '<p><br></p>') { deskripsiInput.value = ''; } else { deskripsiInput.value = quill.root.innerHTML; }
            });

            // helper to upload local images from Quill toolbar
            function selectLocalImage() {
                const input = document.createElement('input');
                input.setAttribute('type', 'file');
                input.setAttribute('accept', 'image/*');
                input.click();

                input.onchange = () => {
                    const file = input.files[0];
                    if (/^image\//.test(file.type)) {
                        saveToServer(file);
                    } else {
                        alert('Anda hanya bisa mengupload file gambar.');
                    }
                };
            }

            function saveToServer(file) {
                const fd = new FormData();
                fd.append('image', file);
                const csrfToken = document.querySelector('meta[name="csrf-token"]').content;

                fetch("{{ route('admin.penginapan.upload.image') }}", {
                    method: 'POST',
                    headers: {
                        'X-CSRF-TOKEN': csrfToken,
                        'Accept': 'application/json'
                    },
                    body: fd
                })
                    .then(response => response.json())
                    .then(result => {
                        if (result.url) {
                            insertToEditor(result.url);
                        } else {
                            alert('Upload gagal: ' + (result.message || 'Error tidak diketahui'));
                        }
                    })
                    .catch(error => {
                        console.error('Error:', error);
                        alert('Terjadi kesalahan saat mengupload gambar.');
                    });
            }

            function insertToEditor(url) {
                const range = quill.getSelection(true);
                quill.insertEmbed(range.index, 'image', url);
                quill.setSelection(range.index + 1);
            }

            quill.getModule('toolbar').addHandler('image', () => {
                selectLocalImage();
            });
        });
    </script>
@endpush