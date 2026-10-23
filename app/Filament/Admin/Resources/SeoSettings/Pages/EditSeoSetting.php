<?php

namespace App\Filament\Admin\Resources\SeoSettings\Pages;

use App\Filament\Admin\Resources\SeoSettingResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\EditRecord;

class EditSeoSetting extends EditRecord
{
    use HandlesTranslations;
    protected static string $resource = SeoSettingResource::class;
}
