<form class="product-buy" wire:submit="add">
    <div class="product-buy-price">
        <span>{{ $price }}</span>
        <small>/ {{ $unit }}</small>
    </div>

    <div>
        <span class="product-label">{{ __('store.product.quantity') }}</span>
        <div class="product-qty">
            <button class="store-icon-btn" type="button" wire:click="decrement" aria-label="{{ __('store.product.decrease') }}">−</button>
            <input class="store-input" name="quantity" inputmode="decimal" wire:model.live="quantity" aria-label="{{ __('store.product.quantity') }}">
            <button class="store-icon-btn" type="button" wire:click="increment" aria-label="{{ __('store.product.increase') }}">+</button>
        </div>
    </div>

    @if($product->secondary_unit)
        <label class="block">
            <span class="product-label">{{ __('store.product.unit') }}</span>
            <select class="store-input" name="unit" wire:model.live="unit">
                <option value="{{ $product->unit }}">{{ $product->unit }}</option>
                <option value="{{ $product->secondary_unit }}">{{ $product->secondary_unit }}</option>
            </select>
        </label>
    @endif

    @if($product->services->isNotEmpty())
        <div class="product-options">
            @foreach($product->services as $service)
                <label class="product-option">
                    <span class="product-option-main">
                        <input type="checkbox" name="service_ids[]" value="{{ $service->id }}" wire:model.live="serviceIds">
                        <span>{{ $service->name }}</span>
                    </span>
                    <span class="product-option-price">
                        @if($service->surcharge_type === 'percent')
                            +{{ rtrim(rtrim((string) $service->surcharge_value, '0'), '.') }}%
                        @else
                            +{{ app(\App\Services\DecimalMath::class)->format((string) $service->surcharge_value) }}
                        @endif
                    </span>
                </label>
            @endforeach
        </div>
    @endif

    @if($product->packagingTypes->isNotEmpty())
        <label class="block">
            <span class="product-label">{{ __('store.product.packaging') }}</span>
            <select class="store-input" name="packaging_id" wire:model.live="packagingId">
                <option value="">{{ __('store.product.no_packaging') }}</option>
                @foreach($product->packagingTypes as $packaging)
                    <option value="{{ $packaging->id }}">
                        {{ $packaging->name }}
                        @if($packaging->surcharge_type === 'percent')
                            (+{{ rtrim(rtrim((string) $packaging->surcharge_value, '0'), '.') }}%)
                        @else
                            (+{{ app(\App\Services\DecimalMath::class)->format((string) $packaging->surcharge_value) }})
                        @endif
                    </option>
                @endforeach
            </select>
        </label>
    @endif

    @error('quantity')<p class="product-error">{{ $message }}</p>@enderror
    @unless($inStock)
        <p class="product-error">{{ __('store.product.out_of_stock') }}</p>
    @endunless
    <button class="store-btn-primary product-add" type="submit" @disabled(! $inStock)>{{ __('store.product.add') }}</button>
</form>
