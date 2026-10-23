<?php

namespace App\Policies;

class CityPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'City';
    }
}
