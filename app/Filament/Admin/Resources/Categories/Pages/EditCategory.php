<?php

namespace App\Filament\Admin\Resources\Categories\Pages;

use App\Filament\Admin\Resources\CategoryResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\EditRecord;

class EditCategory extends EditRecord
{
    use HandlesTranslations;
    protected static string $resource = CategoryResource::class;
}
