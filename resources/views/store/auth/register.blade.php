@extends('layouts.store', ['title' => __('store.auth.create')])
@section('content')
<section class="mx-auto grid max-w-5xl gap-8 px-4 py-12 md:grid-cols-2">
    <div>@if($art = \App\Models\DesignAsset::urlFor('auth_side'))<img class="store-card" src="{{ $art }}" alt="">@endif</div>
    <form class="store-card space-y-3 p-6" method="post" action="{{ route('register') }}">
        @csrf
        <h1 class="text-2xl font-bold">{{ __('store.auth.create') }}</h1>
        <input class="store-input" name="name" required placeholder="{{ __('store.checkout.name') }}">
        <input class="store-input" type="email" name="email" required placeholder="{{ __('store.checkout.email') }}">
        <input class="store-input" name="phone" placeholder="{{ __('store.checkout.phone') }}">
        <input class="store-input" type="password" name="password" required placeholder="{{ __('store.account.password') }}">
        <input class="store-input" type="password" name="password_confirmation" required>
        <button class="store-btn-primary" type="submit">{{ __('store.auth.create') }}</button>
    </form>
</section>
@endsection
