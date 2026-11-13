<?php

namespace App\Filament\Admin\Resources\ProductReviews\Pages;

use App\Filament\Admin\Resources\ProductReviewResource;
use Filament\Resources\Pages\ListRecords;

class ListProductReviews extends ListRecords
{
    protected static string $resource = ProductReviewResource::class;
}
