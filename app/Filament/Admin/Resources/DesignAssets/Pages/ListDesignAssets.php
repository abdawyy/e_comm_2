<?php

namespace App\Filament\Admin\Resources\DesignAssets\Pages;

use App\Filament\Admin\Resources\DesignAssetResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListDesignAssets extends ListRecords
{
    protected static string $resource = DesignAssetResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
