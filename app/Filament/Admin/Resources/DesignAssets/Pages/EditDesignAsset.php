<?php

namespace App\Filament\Admin\Resources\DesignAssets\Pages;

use App\Filament\Admin\Resources\DesignAssetResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\EditRecord;

class EditDesignAsset extends EditRecord
{
    use HandlesTranslations;
    protected static string $resource = DesignAssetResource::class;
}
