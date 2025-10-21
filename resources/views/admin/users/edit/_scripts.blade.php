{{-- resources/views/admin/users/edit/scripts.blade.php --}}
@push('scripts')
    <script>
        // Membantu tombol toggle (Enter/Space) untuk aksesibilitas keyboard
        (function () {
            document.addEventListener('click', function (e) {
                // nothing global; Alpine menghandle toggling. 
            });

            // Tambahkan handler keyboard agar tombol toggle bisa dipicu dengan Enter/Space
            document.addEventListener('keydown', function (e) {
                const target = e.target;
                if (!target) return;
                const isToggleBtn = target.getAttribute && target.getAttribute('aria-label') && target.getAttribute('aria-label').toLowerCase().includes('toggle password');
                if (!isToggleBtn) return;

                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    target.click();
                }
            });
        })();
    </script>
@endpush