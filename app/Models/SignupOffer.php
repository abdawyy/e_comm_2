<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SignupOffer extends Model
{
    protected $fillable = [
        'is_active',
        'delay_seconds',
        'coupon_id',
        'heading_en',
        'heading_ar',
        'body_en',
        'body_ar',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'delay_seconds' => 'integer',
        ];
    }

    public function coupon(): BelongsTo
    {
        return $this->belongsTo(Coupon::class);
    }

    public static function current(): ?self
    {
        return static::query()->with('coupon')->first();
    }

    public function heading(): string
    {
        return app()->getLocale() === 'ar' ? $this->heading_ar : $this->heading_en;
    }

    public function body(): string
    {
        return app()->getLocale() === 'ar' ? $this->body_ar : $this->body_en;
    }

    public function isVisible(): bool
    {
        return $this->is_active
            && $this->delay_seconds > 0
            && $this->coupon
            && $this->coupon->isCurrentlyValid();
    }
}
