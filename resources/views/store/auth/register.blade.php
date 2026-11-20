@extends('layouts.store', ['title' => __('store.auth.create')])
@section('content')
<section class="auth-page">
    <form class="store-card auth-card" method="post" action="{{ route('register') }}">
        @csrf
        <h1>{{ __('store.auth.create') }}</h1>
        <p>{{ __('store.auth.register_help') }}</p>
        <label><span>{{ __('store.checkout.name') }}</span><input class="store-input" name="name" required></label>
        <label><span>{{ __('store.checkout.email') }}</span><input class="store-input" type="email" name="email" required></label>
        <label><span>{{ __('store.checkout.phone') }}</span><input class="store-input" name="phone"></label>
        <label><span>{{ __('store.account.password') }}</span><input class="store-input" type="password" name="password" required></label>
        <label><span>{{ __('store.auth.confirm') }}</span><input class="store-input" type="password" name="password_confirmation" required></label>
        <button class="store-btn-primary" type="submit">{{ __('store.auth.create') }}</button>
        <div class="auth-links"><a href="{{ route('login') }}">{{ __('store.nav.login') }}</a></div>
    </form>
</section>
@endsection
