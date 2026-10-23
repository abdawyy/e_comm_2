<?php

namespace App\Policies;

class ContentStringPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'ContentString';
    }
}
