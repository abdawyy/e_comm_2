<?php

namespace App\Filament\Admin\Resources\ContentStrings\Pages;

use App\Filament\Admin\Resources\ContentStringResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\EditRecord;

class EditContentString extends EditRecord
{
    use HandlesTranslations;
    protected static string $resource = ContentStringResource::class;
}
