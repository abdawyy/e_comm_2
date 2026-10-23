<?php

namespace App\Policies;

class PackagingTypePolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'PackagingType';
    }
}
