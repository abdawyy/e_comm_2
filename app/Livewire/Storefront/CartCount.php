<?php

namespace App\Livewire\Storefront;

use App\Support\SessionCart;
use Illuminate\Support\Facades\Schema;
use Livewire\Attributes\On;
use Livewire\Component;
use Throwable;

class CartCount extends Component
{
    #[On('cart-updated')]
    public function refreshCount(): void {}

    public function render(SessionCart $cart)
    {
        $count = '0';

        try {
            if (Schema::hasTable('products')) {
                $count = $cart->quote()->count();
            }
        } catch (Throwable) {
            $count = '0';
        }

        return view('livewire.storefront.cart-count', [
            'count' => rtrim(rtrim($count, '0'), '.') ?: '0',
        ]);
    }
}
