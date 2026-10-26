import './bootstrap';
import Alpine from 'alpinejs';

document.addEventListener('alpine:init', () => {
    window.Alpine.data('storefront', () => ({
        dark: document.documentElement.classList.contains('dark'),
        drawer: false,
        toasts: [],
        offerOpen: false,
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
            this.scheduleOffer();
        },
        scheduleOffer() {
            const node = document.querySelector('[data-signup-offer]');
            if (! node || localStorage.getItem('signup-offer-done')) {
                return;
            }
            const delay = Math.max(1, Number(node.dataset.delay || 15)) * 1000;
            const started = Number(sessionStorage.getItem('visit-started') || Date.now());
            sessionStorage.setItem('visit-started', String(started));
            const wait = Math.max(0, delay - (Date.now() - started));
            setTimeout(() => {
                if (! localStorage.getItem('signup-offer-done')) {
                    this.offerOpen = true;
                }
            }, wait);
        },
        dismissOffer() {
            this.offerOpen = false;
            localStorage.setItem('signup-offer-done', '1');
        },
        async submitOffer(form) {
            const response = await fetch(form.action, {
                method: 'POST',
                headers: {
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content,
                },
                body: new FormData(form),
            });
            const data = await response.json();
            this.push(data.message || 'OK');
            if (response.ok) {
                this.dismissOffer();
            }
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
