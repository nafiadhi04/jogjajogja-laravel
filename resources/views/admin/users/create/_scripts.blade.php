{{-- Toggle script: tempatkan di stack script layout --}}
@push('scripts')
    <script>
        (function () {
            const toggleBtn = document.getElementById('togglePassword');
            if (!toggleBtn) return;

            const pwd = document.getElementById('password');
            const pwdConfirm = document.getElementById('password_confirmation');
            const iconEye = document.getElementById('iconEye');

            function setIcon(show) {
                // simple swap: eye / eye-off inline SVG path change
                if (!iconEye) return;
                iconEye.innerHTML = show
                    ? `<path stroke-linecap="round" stroke-linejoin="round" d="M3 3l18 18"/><path stroke-linecap="round" stroke-linejoin="round" d="M9.88 9.88a3 3 0 104.24 4.24"/>`
                    : `<path stroke-linecap="round" stroke-linejoin="round" d="M15 12a3 3 0 11-6 0 3 3 0 016 0z"/><path stroke-linecap="round" stroke-linejoin="round" d="M2.458 12C3.732 7.943 7.523 5 12 5c4.477 0 8.268 2.943 9.542 7-1.274 4.057-5.065 7-9.542 7-4.477 0-8.268-2.943-9.542-7z"/>`;
            }

            toggleBtn.addEventListener('click', function (e) {
                const isHidden = (pwd.type === 'password');
                pwd.type = isHidden ? 'text' : 'password';
                pwdConfirm.type = isHidden ? 'text' : 'password';
                toggleBtn.setAttribute('aria-pressed', String(isHidden));
                setIcon(isHidden);
            });

            // accessibility: toggle with Enter/Space when focused
            toggleBtn.addEventListener('keydown', function (e) {
                if (e.key === 'Enter' || e.key === ' ') {
                    e.preventDefault();
                    toggleBtn.click();
                }
            });
        })();
    </script>
@endpush