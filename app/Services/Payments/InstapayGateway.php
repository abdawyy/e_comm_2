<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use App\DTO\PaymentInitiation;
use App\Models\Order;
use App\Models\StoreSetting;

class InstapayGateway implements PaymentGatewayInterface
{
    public function id(): string
    {
        return 'instapay';
    }

    public function enabled(): bool
    {
        return StoreSetting::instapayEnabled();
    }

    public function label(): string
    {
        return __('store.payment.instapay');
    }

    public function initiate(Order $order): PaymentInitiation
    {
        $order->forceFill([
            'payment_gateway' => $this->id(),
            'payment_status' => 'unpaid',
        ])->save();

        return new PaymentInitiation(paymentStatus: 'unpaid');
    }
}
