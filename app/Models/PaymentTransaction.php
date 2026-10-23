<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class PaymentTransaction extends Model
{
    protected $fillable = [
        'order_id',
        'gateway',
        'intention_id',
        'special_reference',
        'amount_cents',
        'status',
        'masked_pan',
        'payload',
        'hmac_valid',
        'paid_at',
        'paymob_order_id',
        'paymob_transaction_id',
    ];

    protected $hidden = [
        'client_secret',
    ];

    protected function casts(): array
    {
        return [
            'payload' => 'array',
            'hmac_valid' => 'boolean',
            'paid_at' => 'datetime',
            'amount_cents' => 'integer',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }
}
