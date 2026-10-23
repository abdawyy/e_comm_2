<?php

namespace App\Filament\Admin\Resources\Packaging\Pages;

use App\Filament\Admin\Resources\PackagingTypeResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListPackaging extends ListRecords
{
    protected static string $resource = PackagingTypeResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
