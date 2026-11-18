<?php

namespace App\Policies;

class AreaPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'Area';
    }
}
