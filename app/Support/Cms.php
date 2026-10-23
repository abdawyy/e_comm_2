<?php

namespace App\Support;

use App\Models\ContentString;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Throwable;

class Cms
{
    public static function text(string $key, ?string $fallback = null): string
    {
        $fallback ??= $key;

        try {
            $value = self::translations($key);
        } catch (Throwable) {
            return $fallback;
        }

        $locale = app()->getLocale();
        $translated = $value[$locale] ?? $value['en'] ?? null;

        return filled($translated) ? (string) $translated : $fallback;
    }

    public static function flush(): void
    {
        Cache::forget('cms.strings');
    }

    /**
     * @return array<string, string>
     */
    private static function translations(string $key): array
    {
        if (! Schema::hasTable('content_strings')) {
            return [];
        }

        /** @var array<string, array<string, string>> $map */
        $map = Cache::rememberForever('cms.strings', function () {
            return ContentString::query()->get()->mapWithKeys(function (ContentString $row) {
                return [$row->key => $row->getTranslations('value')];
            })->all();
        });

        return $map[$key] ?? [];
    }
}
