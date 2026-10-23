<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use App\Exceptions\CartException;

class PaymentGatewayManager
{
    public function __construct(
        private CashOnDeliveryGateway $cod,
        private PaymobCardGateway $paymob,
    ) {}

    /**
     * @return array<int, PaymentGatewayInterface>
     */
    public function all(): array
    {
        return [$this->cod, $this->paymob];
    }

    /**
     * @return array<int, PaymentGatewayInterface>
     */
    public function enabled(): array
    {
        return array_values(array_filter($this->all(), fn (PaymentGatewayInterface $gateway) => $gateway->enabled()));
    }

    public function get(string $id): PaymentGatewayInterface
    {
        foreach ($this->all() as $gateway) {
            if ($gateway->id() === $id && $gateway->enabled()) {
                return $gateway;
            }
        }

        throw new CartException('store.checkout.gateway');
    }
}
