<?php

namespace App\Filament\Admin\Resources\Packaging\Pages;

use App\Filament\Admin\Resources\PackagingTypeResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\CreateRecord;

class CreatePackagingType extends CreateRecord
{
    use HandlesTranslations;
    protected static string $resource = PackagingTypeResource::class;
}
