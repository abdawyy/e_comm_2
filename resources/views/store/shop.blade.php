@extends('layouts.store', ['title' => __('store.nav.shop'), 'seoKey' => 'shop'])
@section('content')
<div class="mx-auto grid max-w-6xl gap-6 px-4 py-8 md:grid-cols-[16rem_1fr]">
    <aside class="store-card h-fit p-4">
        <form method="get" action="{{ route('shop') }}" class="space-y-3">
            <input class="store-input" name="q" value="{{ request('q') }}" placeholder="{{ __('store.nav.search') }}">
            <select class="store-input" name="category">
                <option value="">{{ __('store.shop.all_categories') }}</option>
                @foreach($categories as $category)
                    <option value="{{ $category->id }}" @selected(request('category') == $category->id)>{{ $category->name }}</option>
                @endforeach
            </select>
            <select class="store-input" name="unit">
                <option value="">{{ __('store.shop.any_unit') }}</option>
                @foreach(['kg', 'piece', 'pack', 'hour'] as $unit)
                    <option value="{{ $unit }}" @selected(request('unit') === $unit)>{{ $unit }}</option>
                @endforeach
            </select>
            <label class="flex items-center gap-2 text-sm"><input type="checkbox" name="stock" value="1" @checked(request()->boolean('stock'))> {{ __('store.shop.in_stock') }}</label>
            <button class="store-btn-primary w-full" type="submit">{{ __('store.shop.filter') }}</button>
        </form>
    </aside>
    <div>
        @if($products->isEmpty())
            <div class="store-card p-8 text-center">
                @if($empty = \App\Models\DesignAsset::urlFor('empty_shop'))<img class="mx-auto mb-4 h-40" src="{{ $empty }}" alt="{{ \App\Models\DesignAsset::altFor('empty_shop') }}">@endif
                <p>{{ __('store.shop.empty') }}</p>
            </div>
        @else
            <div class="grid gap-4 sm:grid-cols-2 lg:grid-cols-3">
                @foreach($products as $product)
                    @include('store.partials.product-card', ['product' => $product])
                @endforeach
            </div>
            <div class="mt-6">{{ $products->links() }}</div>
        @endif
    </div>
</div>
@endsection
