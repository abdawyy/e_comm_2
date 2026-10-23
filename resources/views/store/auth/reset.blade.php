@extends('layouts.store', ['title' => __('store.auth.reset')])
@section('content')
<form class="store-card mx-auto mt-12 max-w-md space-y-3 p-6" method="post" action="{{ route('password.update') }}">
    @csrf
    <input type="hidden" name="token" value="{{ $token }}">
    <input class="store-input" type="email" name="email" required>
    <input class="store-input" type="password" name="password" required>
    <input class="store-input" type="password" name="password_confirmation" required>
    <button class="store-btn-primary" type="submit">{{ __('store.auth.reset') }}</button>
</form>
@endsection
