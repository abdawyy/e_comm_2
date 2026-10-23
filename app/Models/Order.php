<?php

namespace App\Models;

use App\Events\OrderStatusChanged;
use Database\Factories\OrderFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class Order extends Model
{
    /** @use HasFactory<OrderFactory> */
    use HasFactory;

    protected $fillable = [
        'user_id',
        'reference',
        'status',
        'payment_gateway',
        'currency',
        'locale',
        'customer_name',
        'customer_email',
        'customer_phone',
        'city_id',
        'city_name',
        'address',
        'subtotal',
        'discount',
        'coupon_code',
        'coupon_id',
        'order_packaging_fee',
        'order_packaging_snapshot',
        'shipping_fee',
        'total',
        'amount_cents',
        'notes',
        'invoice_path',
        'paid_at',
        'confirmation_sent_at',
        'paymob_transaction_id',
        'masked_pan',
    ];

    protected static function booted(): void
    {
        static::updated(function (Order $order): void {
            if ($order->wasChanged('status')) {
                event(new OrderStatusChanged(
                    $order,
                    (string) $order->getOriginal('status'),
                    (string) $order->status,
                ));
            }
        });
    }

    protected function casts(): array
    {
        return [
            'subtotal' => 'decimal:4',
            'discount' => 'decimal:4',
            'order_packaging_fee' => 'decimal:4',
            'shipping_fee' => 'decimal:4',
            'total' => 'decimal:4',
            'order_packaging_snapshot' => 'array',
            'paid_at' => 'datetime',
            'confirmation_sent_at' => 'datetime',
            'amount_cents' => 'integer',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public function items(): HasMany
    {
        return $this->hasMany(OrderItem::class);
    }

    public function orderNotes(): HasMany
    {
        return $this->hasMany(OrderNote::class);
    }

    public function transactions(): HasMany
    {
        return $this->hasMany(PaymentTransaction::class);
    }

    public static function nextReference(): string
    {
        do {
            $reference = chr(random_int(65, 90)).chr(random_int(65, 90)).'-'.str_pad((string) random_int(0, 9999999999), 10, '0', STR_PAD_LEFT);
        } while (static::query()->where('reference', $reference)->exists());

        return $reference;
    }

    public function canCustomerCancel(): bool
    {
        return in_array($this->status, ['pending', 'confirmed'], true) && $this->payment_status !== 'paid';
    }

    public function displayName(): string
    {
        return $this->reference;
    }
}
