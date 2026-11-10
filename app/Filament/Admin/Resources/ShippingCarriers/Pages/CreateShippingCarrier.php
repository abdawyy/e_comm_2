<?php

namespace App\Filament\Admin\Resources\ShippingCarriers\Pages;

use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use App\Filament\Admin\Resources\ShippingCarrierResource;
use Filament\Resources\Pages\CreateRecord;

class CreateShippingCarrier extends CreateRecord
{
    use HandlesTranslations;

    protected static string $resource = ShippingCarrierResource::class;
}
