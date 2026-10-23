<?php

namespace App\Filament\Admin\Resources\ContentStrings\Pages;

use App\Filament\Admin\Resources\ContentStringResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\CreateRecord;

class CreateContentString extends CreateRecord
{
    use HandlesTranslations;
    protected static string $resource = ContentStringResource::class;
}
