<?php

namespace App\Filament\Admin\Resources\SeoSettings\Pages;

use App\Filament\Admin\Resources\SeoSettingResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\CreateRecord;

class CreateSeoSetting extends CreateRecord
{
    use HandlesTranslations;
    protected static string $resource = SeoSettingResource::class;
}
