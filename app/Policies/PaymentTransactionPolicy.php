<?php

namespace App\Policies;

class PaymentTransactionPolicy extends StorePolicy
{
    protected function subject(): string
    {
        return 'PaymentTransaction';
    }
}
