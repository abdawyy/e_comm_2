<?php

namespace App\Filament\Account\Widgets;

use App\Filament\Account\Pages\Profile;
use App\Filament\Account\Resources\InquiryResource;
use App\Filament\Account\Resources\OrderResource;
use Filament\Widgets\Widget;

class AccountHomeWidget extends Widget
{
    protected string $view = 'filament.account.widgets.home';

    protected int|string|array $columnSpan = 'full';

    /**
     * @return array<string, mixed>
     */
    protected function getViewData(): array
    {
        return [
            'name' => auth()->user()?->name,
            'ordersUrl' => OrderResource::getUrl('index'),
            'requestsUrl' => InquiryResource::getUrl('index'),
            'profileUrl' => Profile::getUrl(),
        ];
    }
}
