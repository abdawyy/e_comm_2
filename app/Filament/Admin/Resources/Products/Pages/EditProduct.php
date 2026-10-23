<?php

namespace App\Filament\Admin\Resources\Products\Pages;

use App\Filament\Admin\Resources\ProductResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\EditRecord;

class EditProduct extends EditRecord
{
    use HandlesTranslations;
    protected static string $resource = ProductResource::class;
}
