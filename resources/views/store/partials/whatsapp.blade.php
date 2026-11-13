@php($whatsapp = \App\Models\StoreSetting::whatsappUrl())
@if($whatsapp)
    <style>
        .store-whatsapp {
            position: fixed;
            z-index: 30;
            right: 18px;
            bottom: 18px;
            display: grid;
            width: 52px;
            height: 52px;
            place-items: center;
            border-radius: 999px;
            background: #25d366;
            color: #fff;
            box-shadow: 0 8px 18px rgba(37, 211, 102, .45);
            text-decoration: none;
            opacity: 1;
            transition: opacity .45s ease;
        }
        .store-whatsapp svg {
            width: 30px;
            height: 30px;
            display: block;
            fill: #fff;
        }
        .store-whatsapp:hover,
        .store-whatsapp:focus {
            transform: none;
            width: 52px;
            height: 52px;
        }
        .store-whatsapp.is-hidden,
        .menu-open .store-whatsapp,
        body:has(.signup-offer:not([hidden])) .store-whatsapp,
        body:has(.fi-modal-open) .store-whatsapp {
            opacity: 0;
            pointer-events: none;
        }
        @media (max-width: 899px) {
            .store-hero-visual,
            .store-hero-visual div {
                min-height: 14rem;
            }
            .store-hero-visual {
                margin-bottom: 4.5rem;
            }
        }
        .fi-body .store-whatsapp {
            left: 18px;
            right: auto;
            z-index: 20;
        }
    </style>
    <a class="store-whatsapp" href="{{ $whatsapp }}" target="_blank" rel="noopener" aria-label="{{ __('store.social.whatsapp') }}">
        <svg viewBox="0 0 32 32" aria-hidden="true"><path d="M16.01 3C9.4 3 4.05 8.34 4.05 14.94c0 2.1.55 4.15 1.6 5.95L4 29l8.28-2.16a12.1 12.1 0 0 0 3.73.6h.01c6.61 0 11.96-5.34 11.96-11.94C27.98 8.34 22.62 3 16.01 3zm6.95 16.95c-.28.78-1.62 1.49-2.24 1.59-.58.09-1.3.13-2.1-.13-.48-.16-1.1-.36-1.9-.71-3.34-1.44-5.52-4.81-5.69-5.03-.16-.23-1.34-1.78-1.34-3.4 0-1.62.85-2.41 1.15-2.74.28-.32.74-.47 1.18-.47.14 0 .27 0 .39.01.35.02.51.04.74.58.28.67.95 2.32 1.03 2.49.08.16.14.37.03.59-.11.23-.16.37-.32.57-.16.2-.34.43-.48.58-.16.16-.33.34-.14.65.19.32.84 1.38 1.8 2.24 1.24 1.1 2.28 1.45 2.61 1.61.32.16.51.14.7-.08.19-.23.81-.94 1.02-1.26.22-.32.43-.27.72-.16.3.1 1.88.88 2.2 1.04.32.16.53.24.61.37.08.14.08.79-.2 1.57z"/></svg>
    </a>
    <script>
        document.addEventListener('DOMContentLoaded', () => {
            const footer = document.querySelector('.store-footer');
            const button = document.querySelector('.store-whatsapp');
            if (!footer || !button || !('IntersectionObserver' in window)) {
                return;
            }
            new IntersectionObserver(([entry]) => {
                button.classList.toggle('is-hidden', entry.isIntersecting);
            }, { rootMargin: '0px 0px -80px 0px', threshold: 0 }).observe(footer);
        });
    </script>
@endif
