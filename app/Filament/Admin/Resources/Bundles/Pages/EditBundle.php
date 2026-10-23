<?php

namespace App\Filament\Admin\Resources\Bundles\Pages;

use App\Filament\Admin\Resources\BundleResource;
use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use Filament\Resources\Pages\EditRecord;

class EditBundle extends EditRecord
{
    use HandlesTranslations;
    protected static string $resource = BundleResource::class;
}
