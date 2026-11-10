<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class StoreSetting extends Model
{
    protected function casts(): array
    {
        return [
            'announcement_active' => 'boolean',
        ];
    }

    protected $fillable = [
        'order_email',
        'whatsapp_number',
        'announcement_en',
        'announcement_ar',
        'announcement_active',
    ];

    protected static function booted(): void
    {
        static::saved(function (): void {
            Cache::forget('store.order_email');
            Cache::forget('store.whatsapp_number');
            Cache::forget('store.announcement');
        });
        static::deleted(function (): void {
            Cache::forget('store.order_email');
            Cache::forget('store.whatsapp_number');
            Cache::forget('store.announcement');
        });
    }

    public static function announcement(): ?string
    {
        $cached = Cache::get('store.announcement');

        if (! is_array($cached)) {
            $cached = static::storedAnnouncement();
            Cache::forever('store.announcement', $cached);
        }

        if (static::announcementUnset($cached)) {
            $cached = static::defaultAnnouncement();
        }

        if (! ($cached['active'] ?? false)) {
            return null;
        }

        $locale = app()->getLocale() === 'en' ? 'en' : 'ar';
        $text = trim((string) ($cached[$locale] ?? ''));

        if ($text === '') {
            $text = trim((string) ($cached[$locale === 'ar' ? 'en' : 'ar'] ?? ''));
        }

        return $text !== '' ? $text : null;
    }

    /**
     * @return array{active: bool, en: string, ar: string}
     */
    public static function defaultAnnouncement(): array
    {
        return [
            'active' => true,
            'en' => 'Free shipping on orders of 1000 EGP or more. Discount code: ( CA1 )',
            'ar' => 'شحن مجاني عند الطلب بـ1000 جنيه أو أكثر كود خصم : ( CA1 )',
        ];
    }

    /**
     * @param  array{active?: bool, en?: string, ar?: string}  $cached
     */
    private static function announcementUnset(array $cached): bool
    {
        return trim((string) ($cached['en'] ?? '')) === '' && trim((string) ($cached['ar'] ?? '')) === '';
    }

    /**
     * @return array{active: bool, en: string, ar: string}
     */
    private static function storedAnnouncement(): array
    {
        $empty = ['active' => false, 'en' => '', 'ar' => ''];

        try {
            if (! Schema::hasTable('store_settings') || ! Schema::hasColumn('store_settings', 'announcement_en')) {
                return $empty;
            }
        } catch (Throwable) {
            return $empty;
        }

        $row = static::query()->orderByDesc('id')->first();

        if (! $row) {
            return $empty;
        }

        return [
            'active' => (bool) $row->announcement_active,
            'en' => (string) $row->announcement_en,
            'ar' => (string) $row->announcement_ar,
        ];
    }

    public static function whatsappNumber(): ?string
    {
        $number = Cache::get('store.whatsapp_number');

        if (! is_string($number)) {
            $number = static::storedWhatsapp();
            Cache::forever('store.whatsapp_number', (string) $number);
        }

        $number = trim($number);

        return $number !== '' ? $number : null;
    }

    public static function whatsappUrl(): ?string
    {
        $digits = preg_replace('/\D+/', '', (string) static::whatsappNumber()) ?? '';

        if ($digits !== '') {
            if (str_starts_with($digits, '0')) {
                $digits = '20'.substr($digits, 1);
            }

            return 'https://wa.me/'.$digits;
        }

        try {
            if (! Schema::hasTable('social_links')) {
                return null;
            }
        } catch (Throwable) {
            return null;
        }

        $url = SocialLink::query()->where('platform', 'whatsapp')->where('is_active', true)->value('url');

        return filled($url) ? (string) $url : null;
    }

    private static function storedWhatsapp(): string
    {
        try {
            if (! Schema::hasTable('store_settings') || ! Schema::hasColumn('store_settings', 'whatsapp_number')) {
                return '';
            }
        } catch (Throwable) {
            return '';
        }

        return (string) static::query()->whereNotNull('whatsapp_number')->orderByDesc('id')->value('whatsapp_number');
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
