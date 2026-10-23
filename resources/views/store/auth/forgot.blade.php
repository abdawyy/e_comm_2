@extends('layouts.store', ['title' => __('store.auth.forgot')])
@section('content')
<form class="store-card mx-auto mt-12 max-w-md space-y-3 p-6" method="post" action="{{ route('password.email') }}">
    @csrf
    <h1 class="text-2xl font-bold">{{ __('store.auth.forgot') }}</h1>
    <input class="store-input" type="email" name="email" required>
    <button class="store-btn-primary" type="submit">{{ __('store.auth.reset_send') }}</button>
</form>
@endsection
