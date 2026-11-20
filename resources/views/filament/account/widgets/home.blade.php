<section class="account-home">
    <h2>{{ __('store.account.hello', ['name' => $name]) }}</h2>
    <p>{{ __('store.account.home_lead') }}</p>
    <div class="account-home-grid">
        <a href="{{ $ordersUrl }}">
            <strong>{{ __('store.orders.plural') }}</strong>
            <span>{{ __('store.account.orders_help') }}</span>
        </a>
        <a href="{{ $requestsUrl }}">
            <strong>{{ __('store.account.requests') }}</strong>
            <span>{{ __('store.account.requests_help') }}</span>
        </a>
        <a href="{{ $profileUrl }}">
            <strong>{{ __('store.account.profile') }}</strong>
            <span>{{ __('store.account.profile_help') }}</span>
        </a>
    </div>
</section>
