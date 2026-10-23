<?php

namespace App\Models;

use Database\Factories\SiteThemeFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SiteTheme extends Model
{
    /** @use HasFactory<SiteThemeFactory> */
    use HasFactory;

    protected $fillable = [
        'brand_name',
        'primary',
        'primary_dark',
        'primary_light',
        'accent',
        'accent_light',
        'canvas',
        'surface',
        'border',
        'success',
        'danger',
        'theme_color',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cache::forget('site.theme.v2'));
        static::deleted(fn () => Cache::forget('site.theme.v2'));
    }

    public static function current(): self
    {
        try {
            if (! Schema::hasTable('site_themes')) {
                return static::defaults();
            }
        } catch (Throwable) {
            return static::defaults();
        }

        $cached = Cache::get('site.theme.v2');

        if (is_array($cached)) {
            return (new static)->newFromBuilder($cached);
        }

        $theme = static::query()->first();

        if ($theme) {
            Cache::forever('site.theme.v2', $theme->getAttributes());

            return $theme;
        }

        return static::defaults();
    }

    public static function defaults(): self
    {
        $defaults = config('store.theme_defaults');
        $defaults['brand_name'] = $defaults['brand_name'] ?: config('store.brand_name');

        return new static($defaults);
    }

    public function css(string $field): string
    {
        $value = (string) $this->{$field};
        $fallback = (string) (config('store.theme_defaults.'.$field) ?: '#000000');

        return preg_match('/^#[0-9A-Fa-f]{6}$/', $value) ? $value : $fallback;
    }
}
