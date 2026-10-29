import './bootstrap';
import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('storefront', () => ({
        dark: document.documentElement.classList.contains('dark'),
        drawer: false,
        toasts: [],
        init() {
            window.addEventListener('toast', (event) => {
                const detail = event.detail;
                const message = detail && typeof detail === 'object' ? detail.message : detail;
                if (! message) {
                    return;
                }
                setTimeout(() => {
                    const state = document.body._x_dataStack && document.body._x_dataStack[0];
                    if (state) {
                        state.push(message);
                    }
                }, 40);
            });
        },
        toggleDark() {
            this.dark = !this.dark;
            document.documentElement.classList.toggle('dark', this.dark);
            localStorage.setItem('theme', this.dark ? 'dark' : 'light');
        },
        push(message) {
            const state = (document.body._x_dataStack && document.body._x_dataStack[0]) || this;
            const id = Date.now();
            state.toasts.push({ id, message });
            setTimeout(() => {
                state.toasts = state.toasts.filter((toast) => toast.id !== id);
            }, 2800);
        },
        async add(form) {
            const state = (document.body._x_dataStack && document.body._x_dataStack[0]) || this;
            const body = new FormData(form);
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body,
            });
            const data = await response.json();
            state.push(data.message || 'OK');
            if (response.ok) {
                document.querySelectorAll('[data-cart-count]').forEach((node) => {
                    node.textContent = String(data.count).replace(/\.0+$/, '');
                });
            }
        },
}));

document.addEventListener('DOMContentLoaded', () => {
    if (!window.Alpine) {
        window.Alpine = Alpine;
        Alpine.start();
    }
});
});
