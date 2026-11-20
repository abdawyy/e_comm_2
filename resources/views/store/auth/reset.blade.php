@extends('layouts.store', ['title' => __('store.auth.reset')])
@section('content')
<section class="auth-page">
    <form class="store-card auth-card" method="post" action="{{ route('password.update') }}">
        @csrf
        <h1>{{ __('store.auth.reset') }}</h1>
        <p>{{ __('store.auth.reset_help') }}</p>
        <input type="hidden" name="token" value="{{ $token }}">
        <label><span>{{ __('store.checkout.email') }}</span><input class="store-input" type="email" name="email" value="{{ old('email', request('email')) }}" required></label>
        <label><span>{{ __('store.account.password') }}</span><input class="store-input" type="password" name="password" required></label>
        <label><span>{{ __('store.auth.confirm') }}</span><input class="store-input" type="password" name="password_confirmation" required></label>
        <button class="store-btn-primary" type="submit">{{ __('store.auth.reset') }}</button>
    </form>
</section>
@endsection
