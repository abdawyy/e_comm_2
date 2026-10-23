<?php

namespace App\Policies;

class CategoryPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'Category';
    }
}
