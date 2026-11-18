<?php

namespace App\Policies;

class ProductInquiryPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'ProductInquiry';
    }
}
