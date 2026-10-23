<?php

namespace Database\Factories;

use App\Models\Order;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Order>
 */
class OrderFactory extends Factory
{
    public function definition(): array
    {
        return [
            'reference' => Order::nextReference(),
            'status' => 'pending',
            'payment_gateway' => 'cod',
            'currency' => 'EGP',
            'locale' => 'en',
            'customer_name' => fake()->name(),
            'customer_email' => fake()->safeEmail(),
            'customer_phone' => '01000000000',
            'city_name' => 'Cairo',
            'address' => '12 Nile Street',
            'subtotal' => '10.0000',
            'discount' => '0.0000',
            'order_packaging_fee' => '0.0000',
            'shipping_fee' => '0.0000',
            'total' => '10.0000',
            'amount_cents' => 1000,
        ];
    }

    public function configure(): static
    {
        return $this->afterCreating(function (Order $order): void {
            if ($order->payment_status === null || $order->payment_status === '') {
                $order->forceFill(['payment_status' => 'unpaid'])->save();
            }
        });
    }
}
