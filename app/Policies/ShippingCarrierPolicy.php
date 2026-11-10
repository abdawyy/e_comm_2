<?php

namespace App\Policies;

class ShippingCarrierPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'ShippingCarrier';
    }
}
