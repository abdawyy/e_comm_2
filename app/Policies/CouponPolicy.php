<?php

namespace App\Policies;

class CouponPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'Coupon';
    }
}
