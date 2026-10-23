<?php

namespace App\Filament\Admin\Resources\HomeBanners\Pages;

use App\Filament\Admin\Resources\HomeBannerResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\EditRecord;

class EditHomeBanner extends EditRecord
{
    use HandlesTranslations;
    protected static string $resource = HomeBannerResource::class;
}
