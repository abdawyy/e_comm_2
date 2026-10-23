<?php

namespace App\Policies;

class DesignAssetPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'DesignAsset';
    }
}
