<?php

namespace App\Models;

use App\Models\Concerns\HasPublicImage;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class SeoSetting extends Model
{
    use HasPublicImage, HasTranslations;

    public array $translatable = ['meta_title', 'meta_description'];

    protected $fillable = [
        'route_key',
        'meta_title',
        'meta_description',
        'og_image_path',
    ];

    protected static function booted(): void
    {
        static::updating(function (SeoSetting $seo): void {
            if ($seo->isDirty('og_image_path')) {
                static::deletePublicFile($seo->getOriginal('og_image_path'));
            }
        });

        static::deleting(function (SeoSetting $seo): void {
            static::deletePublicFile($seo->og_image_path);
        });
    }

    public function ogUrl(): ?string
    {
        return $this->publicUrl($this->og_image_path);
    }
}
