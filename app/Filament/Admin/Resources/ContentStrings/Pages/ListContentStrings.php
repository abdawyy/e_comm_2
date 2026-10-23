<?php

namespace App\Filament\Admin\Resources\ContentStrings\Pages;

use App\Filament\Admin\Resources\ContentStringResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListContentStrings extends ListRecords
{
    protected static string $resource = ContentStringResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
