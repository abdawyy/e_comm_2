<?php

namespace App\Http\Controllers;

use App\Events\OrderPaid;
use App\Models\Order;
use App\Models\PaymentTransaction;
use App\Services\Payments\PaymobHmac;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class PaymobWebhookController extends Controller
{
    public function __construct(private PaymobHmac $hmac) {}

    public function __invoke(Request $request): JsonResponse
    {
        $payload = $request->all();
        $transaction = $payload['obj'] ?? $payload;

        if (! is_array($transaction)) {
            return response()->json(['message' => 'Invalid payload'], 401);
        }

        $provided = $request->query('hmac', $request->input('hmac'));

        if (! $this->hmac->valid($transaction, is_string($provided) ? $provided : null)) {
            return response()->json(['message' => 'Invalid signature'], 401);
        }

        $reference = (string) (data_get($transaction, 'order.merchant_order_id')
            ?: data_get($transaction, 'order.special_reference')
            ?: data_get($transaction, 'special_reference'));
        $paymobOrderId = data_get($transaction, 'order.id');

        $local = PaymentTransaction::query()
            ->where(function ($query) use ($reference, $paymobOrderId) {
                if ($reference !== '') {
                    $query->where('special_reference', $reference);
                }

                if ($paymobOrderId) {
                    $method = $reference !== '' ? 'orWhere' : 'where';
                    $query->{$method}('paymob_order_id', (string) $paymobOrderId);
                }
            })
            ->latest('id')
            ->first();

        if (! $local) {
            $order = $reference !== '' ? Order::query()->where('reference', $reference)->first() : null;
            $local = $order?->transactions()->latest('id')->first();
        }

        if (! $local) {
            return response()->json(['message' => 'Transaction not found'], 404);
        }

        $paymobId = (string) data_get($transaction, 'id');
        $success = filter_var(data_get($transaction, 'success'), FILTER_VALIDATE_BOOLEAN);
        $pending = filter_var(data_get($transaction, 'pending'), FILTER_VALIDATE_BOOLEAN);
        $refunded = filter_var(data_get($transaction, 'is_refunded'), FILTER_VALIDATE_BOOLEAN);
        $voided = filter_var(data_get($transaction, 'is_voided'), FILTER_VALIDATE_BOOLEAN);
        $maskedPan = $this->maskPan(data_get($transaction, 'source_data.pan'));
        $notify = false;

        DB::transaction(function () use ($local, $transaction, $paymobId, $success, $pending, $refunded, $voided, $maskedPan, &$notify) {
            $order = Order::query()->lockForUpdate()->find($local->order_id);
            $local->refresh();

            if (! $order) {
                return;
            }

            $local->forceFill([
                'hmac_valid' => true,
                'paymob_transaction_id' => $paymobId !== '' ? $paymobId : $local->paymob_transaction_id,
                'masked_pan' => $maskedPan ?: $local->masked_pan,
                'payload' => [
                    'id' => $paymobId,
                    'success' => $success,
                    'pending' => $pending,
                    'is_refunded' => $refunded,
                    'is_voided' => $voided,
                ],
            ]);

            if ((int) data_get($transaction, 'amount_cents') !== (int) $local->amount_cents) {
                $local->status = 'amount_mismatch';
                $local->save();

                return;
            }

            if ($refunded || $voided) {
                $local->status = 'refunded';
                $local->save();

                if ($order->payment_status !== 'refunded') {
                    $order->forceFill(['payment_status' => 'refunded'])->save();
                    $order->orderNotes()->create([
                        'body' => $voided
                            ? 'Paymob marked the Visa payment voided. No refund API call was made.'
                            : 'Paymob marked the Visa payment refunded. No refund API call was made.',
                        'type' => 'payment',
                    ]);
                }

                return;
            }

            if ($success && ! $pending) {
                if ($order->payment_status === 'paid' && (string) $order->paymob_transaction_id === $paymobId) {
                    $local->status = 'paid';
                    $local->save();

                    return;
                }

                $local->status = 'paid';
                $local->paid_at = now();
                $local->save();
                $order->forceFill([
                    'payment_status' => 'paid',
                    'paid_at' => $order->paid_at ?: now(),
                    'paymob_transaction_id' => $paymobId,
                    'masked_pan' => $maskedPan,
                ])->save();
                $notify = $order->confirmation_sent_at === null;

                return;
            }

            if (! $success) {
                $local->status = 'failed';
                $local->save();
                $order->forceFill([
                    'payment_status' => 'failed',
                    'status' => 'pending',
                ])->save();
            }
        });

        if ($notify) {
            $local->refresh();
            event(new OrderPaid($local->order));
        }

        return response()->json(['ok' => true]);
    }

    private function maskPan(mixed $pan): ?string
    {
        if (! is_string($pan) && ! is_numeric($pan)) {
            return null;
        }

        $value = (string) $pan;

        if (preg_match('/^\d{13,19}$/', $value)) {
            return str_repeat('*', strlen($value) - 4).substr($value, -4);
        }

        return substr($value, 0, 32);
    }
}
