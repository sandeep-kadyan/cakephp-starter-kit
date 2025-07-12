import '../css/app.css';
import './ajaxtable.js';

import Alpine from 'alpinejs';

(() => {
    /**
     * Initializes form submit spinner logic.
     * @returns {void}
     */
    const formSpinner = () => {
        const forms = document.querySelectorAll('form');
        forms.forEach(function (form) {
            form.addEventListener('submit', function (e) {
                let submitBtn = form.querySelector('button[type="submit"]:not([disabled])');
                if (submitBtn) {
                    if (submitBtn.classList.contains('loading')) return;
                    submitBtn.classList.add('loading');
                    submitBtn.disabled = true;
                    submitBtn.dataset.originalContent = submitBtn.innerHTML;
                    submitBtn.innerHTML = '<span class="spinner"></span> Please wait...';
                }
            });
        });
    };

    document.addEventListener('DOMContentLoaded', () => {
        formSpinner();
        // Initialize Alpine.js after all other JS
        Alpine.start();
    });
})();
