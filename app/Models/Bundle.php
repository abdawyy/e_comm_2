<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedSlug;
use App\Models\Concerns\HasPublicImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Bundle extends Model
{
    use HasLocalizedSlug, HasPublicImage, HasTranslations;

    public array $translatable = ['name', 'slug', 'description'];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'price',
        'image_path',
        'image_url',
        'is_subscribable',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price' => 'decimal:4',
            'is_subscribable' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (Bundle $bundle): void {
            if ($bundle->isDirty('image_path')) {
                static::deletePublicFile($bundle->getOriginal('image_path'));
            }
        });

        static::deleting(function (Bundle $bundle): void {
            static::deletePublicFile($bundle->image_path);
        });
    }

    public function items(): HasMany
    {
        return $this->hasMany(BundleItem::class);
    }

    public function coverUrl(): ?string
    {
        return $this->publicUrl($this->image_path, $this->image_url);
    }
}
