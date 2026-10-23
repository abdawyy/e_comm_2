<?php

namespace App\Contracts;

use App\DTO\PaymentInitiation;
use App\Models\Order;

interface PaymentGatewayInterface
{
    public function id(): string;

    public function enabled(): bool;

    public function label(): string;

    public function initiate(Order $order): PaymentInitiation;
}
