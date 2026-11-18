<?php

namespace App\Filament\Admin\Resources\ProductInquiries\Pages;

use App\Filament\Admin\Resources\ProductInquiryResource;
use Filament\Resources\Pages\ListRecords;

class ListProductInquiries extends ListRecords
{
    protected static string $resource = ProductInquiryResource::class;
}
