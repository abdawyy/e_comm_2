@php($offer = \App\Models\SignupOffer::current())
@if($offer && $offer->isVisible())
    <div class="signup-offer" data-signup-offer data-delay="{{ (int) $offer->delay_seconds }}" hidden>
        <form class="signup-offer-card" method="post" action="{{ route('signup-offer.store') }}">
            @csrf
            <button class="signup-offer-close" type="button" data-signup-close aria-label="{{ __('store.signup.close') }}">×</button>
            <p class="store-kicker">{{ $offer->coupon->code }}</p>
            <h2>{{ $offer->heading() }}</h2>
            <p>{{ $offer->body() }}</p>
            <label class="mt-4 block text-sm">
                {{ __('store.signup.email') }}
                <input class="store-input mt-1" type="email" name="email" required placeholder="name@email.com">
            </label>
            <p class="signup-offer-status" data-signup-status hidden></p>
            <button class="store-btn-primary mt-4 w-full" type="submit">{{ __('store.signup.submit') }}</button>
            <a class="mt-3 block text-center text-sm underline" href="{{ route('register') }}">{{ __('store.signup.register') }}</a>
        </form>
    </div>
    <script>
        (function () {
            var node = document.querySelector('[data-signup-offer]');
            if (!node || localStorage.getItem('signup-offer-sent')) {
                return;
            }
            var delay = Math.max(1, Number(node.dataset.delay || 15)) * 1000;
            var started = Number(sessionStorage.getItem('visit-started') || Date.now());
            sessionStorage.setItem('visit-started', String(started));
            var wait = Math.max(0, delay - (Date.now() - started));
            window.setTimeout(function () {
                if (localStorage.getItem('signup-offer-sent') || sessionStorage.getItem('signup-offer-closed')) {
                    return;
                }
                node.hidden = false;
            }, wait);
            node.addEventListener('click', function (event) {
                if (event.target === node) {
                    node.hidden = true;
                    sessionStorage.setItem('signup-offer-closed', '1');
                }
            });
            var close = node.querySelector('[data-signup-close]');
            if (close) {
                close.addEventListener('click', function () {
                    node.hidden = true;
                    sessionStorage.setItem('signup-offer-closed', '1');
                });
            }
            var form = node.querySelector('form');
            form.addEventListener('submit', function (event) {
                event.preventDefault();
                var status = node.querySelector('[data-signup-status]');
                fetch(form.action, {
                    method: 'POST',
                    headers: {
                        'Accept': 'application/json',
                        'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]').content
                    },
                    body: new FormData(form)
                }).then(function (response) {
                    return response.json().then(function (data) {
                        return { ok: response.ok, data: data };
                    });
                }).then(function (result) {
                    if (status) {
                        status.hidden = false;
                        status.textContent = (result.data && result.data.message) || '';
                    }
                    if (result.ok) {
                        localStorage.setItem('signup-offer-sent', '1');
                        window.setTimeout(function () { node.hidden = true; }, 900);
                    }
                });
            });
        })();
    </script>
@endif
