<?php

namespace App\Policies;

class ProductPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'Product';
    }
}
