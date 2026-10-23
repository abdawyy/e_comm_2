<?php

namespace App\Policies;

class SiteThemePolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'SiteTheme';
    }
}
