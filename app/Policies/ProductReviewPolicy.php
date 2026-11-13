<?php

namespace App\Policies;

class ProductReviewPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'ProductReview';
    }
}
