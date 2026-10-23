<?php

namespace App\Policies;

class SeoSettingPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'SeoSetting';
    }
}
