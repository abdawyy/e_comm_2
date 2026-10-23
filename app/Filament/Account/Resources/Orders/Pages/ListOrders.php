<?php

namespace App\Filament\Account\Resources\Orders\Pages;

use App\Filament\Account\Resources\OrderResource;
use Filament\Resources\Pages\ListRecords;

class ListOrders extends ListRecords
{
    protected static string $resource = OrderResource::class;
}
