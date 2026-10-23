<?php

namespace App\Filament\Admin\Resources\SiteVisitors\Pages;

use App\Filament\Admin\Resources\SiteVisitorResource;
use Filament\Actions\CreateAction;
use Filament\Resources\Pages\ListRecords;

class ListSiteVisitors extends ListRecords
{
    protected static string $resource = SiteVisitorResource::class;

    protected function getHeaderActions(): array
    {
        return [
            CreateAction::make(),
        ];
    }
}
