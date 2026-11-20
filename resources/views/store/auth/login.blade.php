@extends('layouts.store', ['title' => __('store.nav.login')])
@section('content')
<section class="auth-page">
    <form class="store-card auth-card" method="post" action="{{ route('login') }}">
        @csrf
        <h1>{{ __('store.nav.login') }}</h1>
        <p>{{ __('store.auth.login_help') }}</p>
        <label><span>{{ __('store.checkout.email') }}</span><input class="store-input" type="email" name="email" required></label>
        <label><span>{{ __('store.account.password') }}</span><input class="store-input" type="password" name="password" required></label>
        <label class="auth-remember"><input type="checkbox" name="remember"> <span>{{ __('store.auth.remember') }}</span></label>
        <button class="store-btn-primary" type="submit">{{ __('store.nav.login') }}</button>
        <div class="auth-links">
            <a href="{{ route('register') }}">{{ __('store.auth.create') }}</a>
            <a href="{{ route('password.request') }}">{{ __('store.auth.forgot') }}</a>
        </div>
    </form>
    <form class="store-card auth-card" method="post" action="{{ route('otp.send') }}">
        @csrf
        <h2>{{ __('store.auth.otp_title') }}</h2>
        <p>{{ __('store.auth.otp_help') }}</p>
        <label><span>{{ __('store.checkout.phone') }}</span><input class="store-input" name="phone" value="{{ session('otp_phone') }}"></label>
        <button class="store-btn-ghost" type="submit">{{ __('store.auth.otp_send') }}</button>
    </form>
    @if(session('otp_phone'))
        <form class="store-card auth-card" method="post" action="{{ route('otp.verify') }}">
            @csrf
            <label><span>{{ __('store.checkout.phone') }}</span><input class="store-input" name="phone" value="{{ session('otp_phone') }}"></label>
            <label><span>{{ __('store.auth.otp_code') }}</span><input class="store-input" name="code" inputmode="numeric"></label>
            <button class="store-btn-primary" type="submit">{{ __('store.auth.otp_verify') }}</button>
        </form>
    @endif
</section>
@endsection
