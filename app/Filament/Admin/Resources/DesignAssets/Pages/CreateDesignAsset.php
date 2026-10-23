<?php

namespace App\Filament\Admin\Resources\DesignAssets\Pages;

use App\Filament\Admin\Resources\DesignAssetResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\CreateRecord;

class CreateDesignAsset extends CreateRecord
{
    use HandlesTranslations;
    protected static string $resource = DesignAssetResource::class;
}
