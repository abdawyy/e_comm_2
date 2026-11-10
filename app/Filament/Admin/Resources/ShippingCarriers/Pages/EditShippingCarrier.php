<?php

namespace App\Filament\Admin\Resources\ShippingCarriers\Pages;

use App\Filament\Admin\Resources\Concerns\HandlesTranslations;
use App\Filament\Admin\Resources\ShippingCarrierResource;
use Filament\Resources\Pages\EditRecord;

class EditShippingCarrier extends EditRecord
{
    use HandlesTranslations;

    protected static string $resource = ShippingCarrierResource::class;
}
