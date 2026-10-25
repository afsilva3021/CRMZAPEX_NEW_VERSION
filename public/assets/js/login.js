(function () {
    'use strict';

    function setupPasswordToggle() {
        const password = document.getElementById('password');
        const toggle = document.getElementById('login-password-toggle');

        if (!password || !toggle) {
            return;
        }

        toggle.addEventListener('click', function () {
            const visible = password.type === 'password';
            password.type = visible ? 'text' : 'password';
            toggle.classList.toggle('is-visible', visible);
            toggle.setAttribute('aria-pressed', String(visible));
            const label = visible ? 'Ocultar senha' : 'Mostrar senha';
            toggle.setAttribute('aria-label', label);
            toggle.setAttribute('title', label);
        });
    }

    if (document.readyState === 'loading') {
        document.addEventListener('DOMContentLoaded', setupPasswordToggle, { once: true });
    } else {
        setupPasswordToggle();
    }
}());
