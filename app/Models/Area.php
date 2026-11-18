<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Spatie\Translatable\HasTranslations;

class Area extends Model
{
    use HasTranslations;

    public array $translatable = ['name'];

    protected $fillable = [
        'city_id',
        'key',
        'name',
        'shipping_fee',
        'is_active',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'shipping_fee' => 'decimal:4',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    public function city(): BelongsTo
    {
        return $this->belongsTo(City::class);
    }
}
