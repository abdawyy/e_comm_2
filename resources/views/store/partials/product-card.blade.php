<article class="store-card store-product flex flex-col">
    <a class="block" href="{{ route('products.show', $product) }}">
        @if($product->coverUrl())
            <img class="h-44 w-full object-cover" src="{{ $product->coverUrl() }}" alt="{{ $product->name }}">
        @else
            <div class="h-44" style="background: linear-gradient(160deg, var(--color-brand), var(--color-accent-light))"></div>
        @endif
        <div class="p-4 pb-2">
            <p class="text-xs uppercase tracking-wide" style="color: var(--color-accent)">{{ $product->unit }}</p>
            <h3 class="mt-1 font-semibold">{{ $product->name }}</h3>
            <p class="mt-2 font-bold">{{ app(\App\Services\DecimalMath::class)->format((string) $product->price_per_unit) }}</p>
        </div>
    </a>
    <form class="mt-auto p-4 pt-2" method="post" action="{{ route('cart.items.store') }}" @submit.prevent="add($el)">
        @csrf
        <input type="hidden" name="product_id" value="{{ $product->id }}">
        <input type="hidden" name="quantity" value="1">
        <input type="hidden" name="unit" value="{{ $product->unit }}">
        <button class="store-btn-primary w-full" type="submit">{{ __('store.product.add') }}</button>
    </form>
</article>
