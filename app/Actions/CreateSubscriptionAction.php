<?php

namespace App\Actions;

use App\DTO\CreateOrderPayload;
use App\Models\Bundle;
use App\Models\Subscription;
use App\Models\User;
use App\Support\Phone;
use Illuminate\Support\Facades\DB;

class CreateSubscriptionAction
{
    public function __construct(private CreateOrderAction $createOrder) {}

    /**
     * @param  array<string, mixed>  $input
     */
    public function execute(User $user, Bundle $bundle, array $input): Subscription
    {
        return DB::transaction(function () use ($user, $bundle, $input) {
            $interval = $input['interval'] === 'weekly' ? 'weekly' : 'monthly';
            $next = $interval === 'weekly' ? now()->addWeek() : now()->addMonth();

            $subscription = Subscription::query()->create([
                'user_id' => $user->id,
                'bundle_id' => $bundle->id,
                'quantity' => $input['quantity'] ?? '1',
                'interval' => $interval,
                'status' => 'active',
                'next_order_at' => $next->toDateString(),
                'payment_gateway' => 'cod',
                'city_id' => $input['city_id'],
                'address' => $input['address'],
                'phone' => Phone::normalize($input['phone']),
                'customer_name' => $input['customer_name'] ?? $user->name,
                'customer_email' => $user->email,
            ]);

            $order = $this->createOrder->execute(new CreateOrderPayload(
                userId: $user->id,
                customerName: $subscription->customer_name,
                customerEmail: $user->email,
                customerPhone: $subscription->phone,
                cityId: (int) $subscription->city_id,
                address: $subscription->address,
                paymentGateway: 'cod',
                lines: [[
                    'kind' => 'bundle',
                    'bundle_id' => $bundle->id,
                    'quantity' => (string) $subscription->quantity,
                ]],
                locale: app()->getLocale(),
                subscriptionRenewal: true,
            ));

            $order->orderNotes()->create([
                'user_id' => $user->id,
                'body' => 'First subscription order. Renewals stay cash on delivery and do not charge a saved card.',
                'type' => 'subscription',
            ]);

            return $subscription;
        });
    }
}
