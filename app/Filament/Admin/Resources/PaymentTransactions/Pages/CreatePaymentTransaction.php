<?php

namespace App\Filament\Admin\Resources\PaymentTransactions\Pages;

use App\Filament\Admin\Resources\PaymentTransactionResource;
use Filament\Resources\Pages\CreateRecord;

class CreatePaymentTransaction extends CreateRecord
{
    protected static string $resource = PaymentTransactionResource::class;
}
