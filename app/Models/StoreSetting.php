<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class StoreSetting extends Model
{
    protected $fillable = [
        'order_email',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('store.order_email'));
        static::deleted(fn () => Cache::forget('store.order_email'));
    }

    public static function orderEmail(): ?string
    {
        $email = Cache::get('store.order_email');

        if (! is_string($email)) {
            $email = static::storedEmail();
            Cache::forever('store.order_email', (string) $email);
        }

        $email = strtolower(trim($email));

        return $email !== '' ? $email : null;
    }

    private static function storedEmail(): string
    {
        try {
            if (! Schema::hasTable('store_settings')) {
                return (string) config('store.admin_email');
            }
        } catch (Throwable) {
            return (string) config('store.admin_email');
        }

        $email = static::query()->value('order_email');

        if (filled($email)) {
            return (string) $email;
        }

        return (string) config('store.admin_email');
    }
}
