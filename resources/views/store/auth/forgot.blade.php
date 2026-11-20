@extends('layouts.store', ['title' => __('store.auth.forgot')])
@section('content')
<section class="auth-page">
    <form class="store-card auth-card" method="post" action="{{ route('password.email') }}">
        @csrf
        <h1>{{ __('store.auth.forgot') }}</h1>
        <p>{{ __('store.auth.forgot_help') }}</p>
        <label><span>{{ __('store.checkout.email') }}</span><input class="store-input" type="email" name="email" required></label>
        <button class="store-btn-primary" type="submit">{{ __('store.auth.reset_send') }}</button>
        <div class="auth-links"><a href="{{ route('login') }}">{{ __('store.nav.login') }}</a></div>
    </form>
</section>
@endsection
