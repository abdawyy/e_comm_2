<?php

namespace App\Policies;

class PreparationServicePolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'PreparationService';
    }
}
