<?php

namespace App\Policies;

class OrderPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'Order';
    }
}
