<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use App\DTO\PaymentInitiation;
use App\Models\Order;

class CashOnDeliveryGateway implements PaymentGatewayInterface
{
    public function id(): string
    {
        return 'cod';
    }

    public function enabled(): bool
    {
        return true;
    }

    public function label(): string
    {
        return __('store.payment.cod');
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
