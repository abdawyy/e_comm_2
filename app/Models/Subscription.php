<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class Subscription extends Model
{
    protected $fillable = [
        'user_id',
        'bundle_id',
        'quantity',
        'interval',
        'status',
        'next_order_at',
        'payment_gateway',
        'city_id',
        'address',
        'phone',
        'customer_name',
        'customer_email',
    ];

    protected function casts(): array
    {
        return [
            'quantity' => 'decimal:4',
            'next_order_at' => 'date',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
