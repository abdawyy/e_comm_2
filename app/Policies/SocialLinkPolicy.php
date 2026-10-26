<?php

namespace App\Policies;

class SocialLinkPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'SocialLink';
    }
}
