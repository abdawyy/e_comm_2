<?php

namespace App\Console\Commands;

use App\Actions\CreateOrderAction;
use App\DTO\CreateOrderPayload;
use App\Events\OrderCreated;
use App\Models\Subscription;
use Illuminate\Console\Command;

class ProcessSubscriptions extends Command
{
    protected $signature = 'store:process-subscriptions';

    protected $description = 'Create due subscription renewal orders without charging saved cards';

    public function handle(CreateOrderAction $createOrder): int
    {
        $due = Subscription::query()
            ->with('bundle')
            ->where('status', 'active')
            ->whereDate('next_order_at', '<=', now()->toDateString())
            ->get();

        foreach ($due as $subscription) {
            if (! $subscription->bundle || ! $subscription->bundle->is_active) {
                continue;
            }

            $order = $createOrder->execute(new CreateOrderPayload(
                userId: $subscription->user_id,
                customerName: $subscription->customer_name,
                customerEmail: $subscription->customer_email,
                customerPhone: $subscription->phone,
                cityId: (int) $subscription->city_id,
                address: $subscription->address,
                paymentGateway: 'cod',
                lines: [[
                    'kind' => 'bundle',
                    'bundle_id' => $subscription->bundle_id,
                    'quantity' => (string) $subscription->quantity,
                ]],
                locale: 'en',
                notes: 'Subscription renewal. Cash on delivery — saved cards are not charged.',
                subscriptionRenewal: true,
            ));

            event(new OrderCreated($order));

            $subscription->next_order_at = $subscription->interval === 'weekly'
                ? $subscription->next_order_at->copy()->addWeek()
                : $subscription->next_order_at->copy()->addMonth();
            $subscription->save();
            $this->info('Renewed '.$subscription->id.' as '.$order->reference);
        }

        return self::SUCCESS;
    }
}
