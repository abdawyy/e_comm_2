<?php

namespace App\Filament\Admin\Resources\Services\Pages;

use App\Filament\Admin\Resources\PreparationServiceResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\CreateRecord;

class CreatePreparationService extends CreateRecord
{
    use HandlesTranslations;
    protected static string $resource = PreparationServiceResource::class;
}
