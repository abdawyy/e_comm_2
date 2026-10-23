<?php

namespace App\Policies;

class SiteVisitorPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'SiteVisitor';
    }
}
