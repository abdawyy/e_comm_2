<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class ShippingCarrier extends Model
{
    use HasTranslations;

    public array $translatable = ['name'];

    public function setFeeAttribute(mixed $value): void
    {
        $this->attributes['fee'] = ($value === '' || $value === null) ? null : $value;
    }

    protected $fillable = [
        'code',
        'name',
        'fee',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'fee' => 'decimal:4',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function orders(): HasMany
    {
        return $this->hasMany(Order::class);
    }
}
