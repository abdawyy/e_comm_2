<div class="store-search relative">
    <svg class="store-search-icon" viewBox="0 0 24 24" aria-hidden="true"><circle cx="11" cy="11" r="6.5" fill="none" stroke="currentColor" stroke-width="1.8"/><path d="M16 16.5 20 20.5" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linecap="round"/></svg>
    <input class="store-input store-search-input" type="search" wire:model.live.debounce.300ms="q" placeholder="{{ __('store.nav.search') }}" aria-label="{{ __('store.nav.search') }}">
    @if($results->isNotEmpty())
        <div class="store-card absolute z-30 mt-2 w-full p-2">
            @foreach($results as $product)
                <a class="block rounded-lg px-3 py-2 hover:bg-[var(--color-canvas)]" href="{{ route('products.show', $product) }}">{{ $product->name }}</a>
            @endforeach
        </div>
    @endif
</div>
