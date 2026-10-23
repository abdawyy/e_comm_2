<?php

namespace App\Filament\Admin\Resources\SeoSettings\Pages;

use App\Filament\Admin\Resources\SeoSettingResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSeoSettings extends ListRecords
{
    protected static string $resource = SeoSettingResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
