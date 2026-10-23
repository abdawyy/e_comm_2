<?php

namespace App\Filament\Admin\Resources\Packaging\Pages;

use App\Filament\Admin\Resources\PackagingTypeResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\EditRecord;

class EditPackagingType extends EditRecord
{
    use HandlesTranslations;
    protected static string $resource = PackagingTypeResource::class;
}
