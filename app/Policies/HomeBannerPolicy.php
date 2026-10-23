<?php

namespace App\Policies;

class HomeBannerPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'HomeBanner';
    }
}
