@extends('layouts.store', ['title' => __('store.nav.login')])
@section('content')
<section class="mx-auto grid max-w-5xl items-center gap-8 px-4 py-12 md:grid-cols-2">
    <div>
        @if($art = \App\Models\DesignAsset::urlFor('auth_side'))<img class="store-card" src="{{ $art }}" alt="{{ \App\Models\DesignAsset::altFor('auth_side') }}">@endif
    </div>
    <form class="store-card space-y-3 p-6" method="post" action="{{ route('login') }}">
        @csrf
        <h1 class="text-2xl font-bold">{{ __('store.nav.login') }}</h1>
        <input class="store-input" type="email" name="email" required placeholder="{{ __('store.checkout.email') }}">
        <input class="store-input" type="password" name="password" required placeholder="{{ __('store.account.password') }}">
        <label class="text-sm"><input type="checkbox" name="remember"> {{ __('store.auth.remember') }}</label>
        <button class="store-btn-primary" type="submit">{{ __('store.nav.login') }}</button>
        <a class="block text-sm" href="{{ route('register') }}">{{ __('store.auth.create') }}</a>
        <a class="block text-sm" href="{{ route('password.request') }}">{{ __('store.auth.forgot') }}</a>
    </form>
    <form class="store-card space-y-3 p-6 md:col-start-2" method="post" action="{{ route('otp.send') }}">
        @csrf
        <h2 class="font-semibold">{{ __('store.auth.otp_title') }}</h2>
        <input class="store-input" name="phone" placeholder="{{ __('store.checkout.phone') }}">
        <button class="store-btn-ghost" type="submit">{{ __('store.auth.otp_send') }}</button>
    </form>
    <form class="store-card space-y-3 p-6 md:col-start-2" method="post" action="{{ route('otp.verify') }}">
        @csrf
        <input class="store-input" name="phone" value="{{ session('otp_phone') }}" placeholder="{{ __('store.checkout.phone') }}">
        <input class="store-input" name="code" placeholder="{{ __('store.auth.otp_code') }}">
        <button class="store-btn-accent" type="submit">{{ __('store.auth.otp_verify') }}</button>
    </form>
</section>
@endsection
