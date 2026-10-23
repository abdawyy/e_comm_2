<?php

namespace App\Services\Payments;

use App\Contracts\PaymentGatewayInterface;
use App\DTO\PaymentInitiation;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Support\Phone;
use Illuminate\Support\Facades\Http;
use Illuminate\Support\Str;

class PaymobCardGateway implements PaymentGatewayInterface
{
    public function id(): string
    {
        return 'paymob_card';
    }

    public function enabled(): bool
    {
        $config = config('payments.paymob');

        return (bool) ($config['enabled'] ?? false)
            && filled($config['api_key'] ?? null)
            && filled($config['secret_key'] ?? null)
            && filled($config['public_key'] ?? null)
            && filled($config['hmac_secret'] ?? null)
            && filled($config['card_integration_id'] ?? null);
    }

    public function label(): string
    {
        return __('store.payment.visa');
    }

    public function initiate(Order $order): PaymentInitiation
    {
        $config = config('payments.paymob');
        $cents = (int) $order->amount_cents;
        $integrationId = (int) $config['card_integration_id'];

        $transaction = PaymentTransaction::query()->create([
            'order_id' => $order->id,
            'gateway' => $this->id(),
            'special_reference' => $order->reference,
            'amount_cents' => $cents,
            'status' => 'pending',
        ]);

        $notification = $config['notification_url'] ?: route('payments.paymob.webhook');
        $redirection = $config['redirection_url'] ?: route('checkout.paymob.return', ['reference' => $order->reference]);

        $name = trim($order->customer_name);
        $parts = preg_split('/\s+/', $name, 2) ?: [$name];
        $first = $parts[0] !== '' ? $parts[0] : 'Customer';
        $last = $parts[1] ?? 'NA';

        $payload = [
            'amount' => $cents,
            'currency' => $config['currency'] ?: 'EGP',
            'payment_methods' => [$integrationId],
            'items' => [[
                'name' => Str::limit('Order '.$order->reference, 50, ''),
                'amount' => $cents,
                'quantity' => 1,
                'description' => 'Order '.$order->reference,
            ]],
            'billing_data' => [
                'first_name' => $first,
                'last_name' => $last,
                'email' => $order->customer_email ?: 'guest@example.test',
                'phone_number' => Phone::e164($order->customer_phone),
                'country' => 'EG',
                'city' => $order->city_name ?: 'NA',
                'street' => $order->address ?: 'NA',
                'apartment' => 'NA',
                'floor' => 'NA',
                'building' => 'NA',
                'postal_code' => 'NA',
                'state' => 'NA',
            ],
            'special_reference' => $order->reference,
            'notification_url' => $notification,
            'redirection_url' => $redirection,
        ];

        try {
            $response = Http::withToken((string) $config['secret_key'], 'Token')
                ->acceptJson()
                ->asJson()
                ->timeout(20)
                ->post($config['base_url'].'/v1/intention/', $payload);
        } catch (\Throwable $exception) {
            $transaction->forceFill([
                'status' => 'failed',
                'payload' => ['error' => 'http_exception'],
            ])->save();

            return new PaymentInitiation(
                paymentStatus: 'pending',
                failed: true,
                message: __('store.payment.gateway_failed'),
            );
        }

        if (! $response->successful()) {
            $transaction->forceFill([
                'status' => 'failed',
                'payload' => ['http_status' => $response->status()],
            ])->save();

            return new PaymentInitiation(
                paymentStatus: 'pending',
                failed: true,
                message: __('store.payment.gateway_failed'),
            );
        }

        $body = $response->json();
        $clientSecret = (string) data_get($body, 'client_secret');
        $intentionId = (string) data_get($body, 'id');

        if ($clientSecret === '') {
            $transaction->forceFill([
                'status' => 'failed',
                'intention_id' => $intentionId !== '' ? $intentionId : null,
                'payload' => ['http_status' => $response->status(), 'error' => 'missing_client_secret'],
            ])->save();

            return new PaymentInitiation(
                paymentStatus: 'pending',
                failed: true,
                message: __('store.payment.gateway_failed'),
            );
        }

        $transaction->forceFill([
            'intention_id' => $intentionId,
            'client_secret' => $clientSecret,
            'paymob_order_id' => data_get($body, 'intention_order_id') ? (string) data_get($body, 'intention_order_id') : null,
            'status' => 'pending',
            'payload' => [
                'intention_id' => $intentionId,
                'http_status' => $response->status(),
            ],
        ])->save();

        $order->forceFill([
            'payment_gateway' => $this->id(),
            'payment_status' => 'pending',
            'status' => 'pending',
        ])->save();

        $redirect = $config['base_url'].'/unifiedcheckout/?publicKey='.rawurlencode((string) $config['public_key']).'&clientSecret='.rawurlencode($clientSecret);

        return new PaymentInitiation(paymentStatus: 'pending', redirectUrl: $redirect);
    }
}
