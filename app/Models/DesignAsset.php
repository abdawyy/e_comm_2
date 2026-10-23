<?php

namespace App\Models;

use App\Models\Concerns\HasPublicImage;
use Database\Factories\DesignAssetFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Schema;
use Spatie\Translatable\HasTranslations;
use Throwable;

class DesignAsset extends Model
{
    /** @use HasFactory<DesignAssetFactory> */
    use HasFactory, HasPublicImage, HasTranslations;

    public const KEYS = [
        'logo',
        'logo_dark',
        'favicon',
        'hero',
        'og_default',
        'empty_cart',
        'empty_shop',
        'auth_side',
        'footer_mark',
    ];

    public array $translatable = ['alt'];

    protected $fillable = [
        'key',
        'image_path',
        'alt',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (DesignAsset $asset): void {
            if ($asset->isDirty('image_path')) {
                static::deletePublicFile($asset->getOriginal('image_path'));
            }
        });

        static::saved(fn () => Cache::forget('design.assets.v2'));
        static::deleted(function (DesignAsset $asset): void {
            static::deletePublicFile($asset->image_path);
            Cache::forget('design.assets.v2');
        });
    }

    public function url(): ?string
    {
        if (! $this->is_active) {
            return null;
        }

        return $this->publicUrl($this->image_path);
    }

    public static function urlFor(string $key): ?string
    {
        $asset = static::activeMap()[$key] ?? null;

        return $asset?->url();
    }

    public static function altFor(string $key): string
    {
        $asset = static::activeMap()[$key] ?? null;

        if (! $asset) {
            return '';
        }

        $locale = app()->getLocale();
        $alt = $asset->getTranslations('alt');

        return (string) ($alt[$locale] ?? $alt['en'] ?? '');
    }

    /**
     * @return array<string, self>
     */
    public static function activeMap(): array
    {
        try {
            if (! Schema::hasTable('design_assets')) {
                return [];
            }
        } catch (Throwable) {
            return [];
        }

        /** @var array<int, array<string, mixed>> $rows */
        $rows = Cache::rememberForever('design.assets.v2', function () {
            return static::query()->get()->map(fn (self $asset) => $asset->getAttributes())->all();
        });

        $map = [];

        foreach ($rows as $attributes) {
            $asset = (new static)->newFromBuilder($attributes);
            $map[$asset->key] = $asset;
        }

        return $map;
    }
}
