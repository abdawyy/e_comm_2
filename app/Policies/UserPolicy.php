<?php

namespace App\Policies;

class UserPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'User';
    }
}
