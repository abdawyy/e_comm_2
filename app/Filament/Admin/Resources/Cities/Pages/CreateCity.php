<?php

namespace App\Filament\Admin\Resources\Cities\Pages;

use App\Filament\Admin\Resources\CityResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\CreateRecord;

class CreateCity extends CreateRecord
{
    use HandlesTranslations;
    protected static string $resource = CityResource::class;
}
