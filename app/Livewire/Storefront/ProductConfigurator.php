<?php

namespace App\Livewire\Storefront;

use App\Exceptions\CartException;
use App\Models\Product;
use App\Services\DecimalMath;
use App\Services\PricingService;
use App\Support\SessionCart;
use Livewire\Component;

class ProductConfigurator extends Component
{
    public Product $product;

    public string $quantity = '1';

    public string $unit = '';

    public array $serviceIds = [];

    public ?int $packagingId = null;

    public function mount(Product $product): void
    {
        $this->product = $product;
        $this->unit = $product->unit;
    }

    public function estimate(PricingService $pricing, DecimalMath $math): string
    {
        $line = $pricing->resolveLine([
            'kind' => 'product',
            'product_id' => $this->product->id,
            'quantity' => $this->quantity,
            'unit' => $this->unit,
            'service_ids' => $this->serviceIds,
            'packaging_id' => $this->packagingId,
        ]);

        return $line ? $math->format($line->lineSubtotal) : $math->format('0');
    }

    public function add(SessionCart $cart): void
    {
        try {
            $quote = $cart->add([
                'product_id' => $this->product->id,
                'quantity' => $this->quantity,
                'unit' => $this->unit,
                'service_ids' => $this->serviceIds,
                'packaging_id' => $this->packagingId,
            ]);
            $this->dispatch('cart-updated', count: $quote->count());
            $this->dispatch('toast', message: __('store.cart.added'));
        } catch (CartException $exception) {
            $this->addError('quantity', __($exception->errorKey));
        }
    }

    public function increment(DecimalMath $math): void
    {
        $next = $math->add($this->quantity === '' ? '0' : $this->quantity, '1');

        if ($math->comp($next, '10000') > 0) {
            return;
        }

        $this->quantity = $this->trimQuantity($next);
    }

    public function decrement(DecimalMath $math): void
    {
        $next = $math->sub($this->quantity === '' ? '1' : $this->quantity, '1');
        $this->quantity = $math->comp($next, '0') <= 0 ? '1' : $this->trimQuantity($next);
    }

    public function render(PricingService $pricing, DecimalMath $math)
    {
        $inStock = ! $this->product->track_stock || $math->comp((string) $this->product->stock_quantity, '0') > 0;

        return view('livewire.storefront.product-configurator', [
            'price' => $this->estimate($pricing, $math),
            'inStock' => $inStock,
        ]);
    }

    private function trimQuantity(string $value): string
    {
        $trimmed = rtrim(rtrim($value, '0'), '.');

        return $trimmed === '' || $trimmed === '-' ? '0' : $trimmed;
    }
}
