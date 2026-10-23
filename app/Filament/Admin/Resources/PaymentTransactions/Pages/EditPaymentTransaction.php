<?php

namespace App\Filament\Admin\Resources\PaymentTransactions\Pages;

use App\Filament\Admin\Resources\PaymentTransactionResource;
use Filament\Resources\Pages\EditRecord;

class EditPaymentTransaction extends EditRecord
{
    protected static string $resource = PaymentTransactionResource::class;
}
