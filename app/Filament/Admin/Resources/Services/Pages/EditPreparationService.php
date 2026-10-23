<?php

namespace App\Filament\Admin\Resources\Services\Pages;

use App\Filament\Admin\Resources\PreparationServiceResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\EditRecord;

class EditPreparationService extends EditRecord
{
    use HandlesTranslations;
    protected static string $resource = PreparationServiceResource::class;
}
