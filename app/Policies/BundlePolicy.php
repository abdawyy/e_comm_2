<?php

namespace App\Policies;

class BundlePolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'Bundle';
    }
}
