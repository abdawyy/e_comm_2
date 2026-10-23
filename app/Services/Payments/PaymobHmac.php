<?php

namespace App\Services\Payments;

class PaymobHmac
{
    /**
     * @var array<int, string>
     */
    public const FIELDS = [
        'amount_cents',
        'created_at',
        'currency',
        'error_occured',
        'has_parent_transaction',
        'id',
        'integration_id',
        'is_3d_secure',
        'is_auth',
        'is_capture',
        'is_refunded',
        'is_standalone_payment',
        'is_voided',
        'order.id',
        'owner',
        'pending',
        'source_data.pan',
        'source_data.sub_type',
        'source_data.type',
        'success',
    ];

    /**
     * @var array<int, string>
     */
    private const BOOLEAN_FIELDS = [
        'error_occured',
        'has_parent_transaction',
        'is_3d_secure',
        'is_auth',
        'is_capture',
        'is_refunded',
        'is_standalone_payment',
        'is_voided',
        'pending',
        'success',
    ];

    /**
     * @param  array<string, mixed>  $transaction
     */
    public function concatenate(array $transaction): string
    {
        $payload = '';

        foreach (self::FIELDS as $field) {
            $payload .= $this->stringify($field, data_get($transaction, $field));
        }

        return $payload;
    }

    /**
     * @param  array<string, mixed>  $transaction
     */
    public function sign(array $transaction, string $secret): string
    {
        return hash_hmac('sha512', $this->concatenate($transaction), $secret);
    }

    /**
     * @param  array<string, mixed>  $transaction
     */
    public function valid(array $transaction, ?string $provided, ?string $secret = null): bool
    {
        $secret ??= (string) config('payments.paymob.hmac_secret');

        if ($provided === null || $provided === '' || $secret === '') {
            return false;
        }

        $expected = $this->sign($transaction, $secret);

        return hash_equals($expected, $provided);
    }

    private function stringify(string $field, mixed $value): string
    {
        if (in_array($field, self::BOOLEAN_FIELDS, true)) {
            return filter_var($value, FILTER_VALIDATE_BOOLEAN) ? 'true' : 'false';
        }

        if ($value === null) {
            return '';
        }

        if (is_bool($value)) {
            return $value ? 'true' : 'false';
        }

        return (string) $value;
    }
}
