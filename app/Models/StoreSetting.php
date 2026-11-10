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
            'invoice_pdf' => 'array',
        ];
    }

    protected $fillable = [
        'order_email',
        'whatsapp_number',
        'announcement_en',
        'announcement_ar',
        'announcement_active',
        'announcement_color',
        'announcement_opacity',
        'invoice_pdf',
    ];

    protected static function booted(): void
    {
        static::saved(function (): void {
            Cache::forget('store.order_email');
            Cache::forget('store.whatsapp_number');
            Cache::forget('store.announcement');
            Cache::forget('store.invoice_pdf');
        });
        static::deleted(function (): void {
            Cache::forget('store.order_email');
            Cache::forget('store.whatsapp_number');
            Cache::forget('store.announcement');
            Cache::forget('store.invoice_pdf');
        });
    }

    public static function announcement(): ?string
    {
        $cached = static::announcementState();

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

    public static function announcementBackground(): string
    {
        $cached = static::announcementState();
        $color = static::hexColor((string) ($cached['color'] ?? '')) ?? '#eeddb0';
        $opacity = max(0, min(100, (int) ($cached['opacity'] ?? 100))) / 100;
        $red = hexdec(substr($color, 1, 2));
        $green = hexdec(substr($color, 3, 2));
        $blue = hexdec(substr($color, 5, 2));

        return 'rgba('.$red.','.$green.','.$blue.','.number_format($opacity, 2, '.', '').')';
    }

    /**
     * @return array{active: bool, en: string, ar: string, color: string, opacity: int}
     */
    private static function announcementState(): array
    {
        $cached = Cache::get('store.announcement');

        if (! is_array($cached)) {
            $cached = static::storedAnnouncement();
            Cache::forever('store.announcement', $cached);
        }

        if (static::announcementUnset($cached)) {
            return static::defaultAnnouncement();
        }

        return $cached;
    }

    /**
     * @return array{active: bool, en: string, ar: string, color: string, opacity: int}
     */
    public static function defaultAnnouncement(): array
    {
        return [
            'active' => true,
            'en' => 'Free shipping on orders of 1000 EGP or more. Discount code: ( CA1 )',
            'ar' => 'شحن مجاني عند الطلب بـ1000 جنيه أو أكثر كود خصم : ( CA1 )',
            'color' => '#eeddb0',
            'opacity' => 100,
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
     * @return array<string, mixed>
     */
    public static function invoiceProfile(): array
    {
        $cached = Cache::get('store.invoice_pdf');

        if (! is_array($cached)) {
            $cached = static::storedInvoice();
            Cache::forever('store.invoice_pdf', $cached);
        }

        return static::mergeInvoice($cached);
    }

    public static function invoiceLine(string $locale, string $key): string
    {
        $profile = static::invoiceProfile();
        $locale = $locale === 'en' ? 'en' : 'ar';
        $custom = trim((string) ($profile[$locale][$key] ?? ''));

        return $custom !== '' ? $custom : (string) __('store.invoice.'.$key, [], $locale);
    }

    /**
     * @return array<string, mixed>
     */
    public static function invoiceDefaults(): array
    {
        return [
            'paper' => '#fdfbf6',
            'accent' => '#492b18',
            'titles' => '#492b18',
            'bar_text' => '#ffffff',
            'card' => '#f4ebe1',
            'border' => '#886d5a',
            'show_qr' => true,
            'show_tax' => true,
            'tax_percent' => '0',
            'seller_phone' => '',
            'seller_tax' => '',
            'contact' => '',
            'ar' => ['title' => '', 'tagline' => '', 'policy' => '', 'thanks' => '', 'address' => ''],
            'en' => ['title' => '', 'tagline' => '', 'policy' => '', 'thanks' => '', 'address' => ''],
        ];
    }

    /**
     * @param  array<string, mixed>  $stored
     * @return array<string, mixed>
     */
    private static function mergeInvoice(array $stored): array
    {
        $defaults = static::invoiceDefaults();
        $merged = array_replace($defaults, $stored);
        foreach (['paper', 'accent', 'titles', 'bar_text', 'card', 'border'] as $color) {
            $merged[$color] = static::hexColor((string) ($merged[$color] ?? '')) ?? $defaults[$color];
        }
        $merged['show_qr'] = (bool) ($merged['show_qr'] ?? true);
        $merged['show_tax'] = (bool) ($merged['show_tax'] ?? true);
        $percent = trim((string) ($merged['tax_percent'] ?? '0'));
        $merged['tax_percent'] = preg_match('/^\d+(\.\d{1,2})?$/', $percent) === 1 ? $percent : '0';
        foreach (['ar', 'en'] as $locale) {
            $text = is_array($merged[$locale] ?? null) ? $merged[$locale] : [];
            $merged[$locale] = array_replace($defaults[$locale], $text);
        }

        return $merged;
    }

    /**
     * @return array<string, mixed>
     */
    private static function storedInvoice(): array
    {
        try {
            if (! Schema::hasTable('store_settings') || ! Schema::hasColumn('store_settings', 'invoice_pdf')) {
                return [];
            }
        } catch (Throwable) {
            return [];
        }

        $row = static::query()->orderByDesc('id')->first();
        $value = $row?->invoice_pdf;

        return is_array($value) ? $value : [];
    }

    private static function hexColor(string $color): ?string
    {
        return preg_match('/^#[0-9A-Fa-f]{6}$/', $color) === 1 ? strtolower($color) : null;
    }

    /**
     * @return array{active: bool, en: string, ar: string, color: string, opacity: int}
     */
    private static function storedAnnouncement(): array
    {
        $empty = ['active' => false, 'en' => '', 'ar' => '', 'color' => '#eeddb0', 'opacity' => 100];

        try {
            if (! Schema::hasTable('store_settings') || ! Schema::hasColumn('store_settings', 'announcement_en')) {
                return $empty;
            }

            $hasColor = Schema::hasColumn('store_settings', 'announcement_color');
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
            'color' => $hasColor ? (static::hexColor((string) $row->announcement_color) ?? '#eeddb0') : '#eeddb0',
            'opacity' => $hasColor ? max(0, min(100, (int) $row->announcement_opacity)) : 100,
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
