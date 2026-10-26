<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class OrderItem extends Model
{
    protected $fillable = [
        'order_id',
        'kind',
        'product_id',
        'bundle_id',
        'name',
        'unit',
        'unit_price',
        'quantity',
        'services_snapshot',
        'packaging_snapshot',
        'line_base',
        'services_fee',
        'packaging_fee',
        'line_subtotal',
    ];

    protected function casts(): array
    {
        return [
            'name' => 'array',
            'unit_price' => 'decimal:4',
            'quantity' => 'decimal:4',
            'services_snapshot' => 'array',
            'packaging_snapshot' => 'array',
            'line_base' => 'decimal:4',
            'services_fee' => 'decimal:4',
            'packaging_fee' => 'decimal:4',
            'line_subtotal' => 'decimal:4',
        ];
    }

    public function order(): BelongsTo
    {
        return $this->belongsTo(Order::class);
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function bundle(): BelongsTo
    {
        return $this->belongsTo(Bundle::class);
    }

    public function localizedName(?string $locale = null): string
    {
        $names = $this->name ?? [];
        $locale = $locale ?: app()->getLocale();

        return (string) ($names[$locale] ?? $names['en'] ?? '');
    }
}
