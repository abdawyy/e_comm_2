<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedSlug;
use App\Models\Concerns\HasPublicImage;
use Database\Factories\CategoryFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Category extends Model
{
    /** @use HasFactory<CategoryFactory> */
    use HasFactory, HasLocalizedSlug, HasPublicImage, HasTranslations;

    public array $translatable = ['name', 'slug', 'description'];

    protected $fillable = [
        'name',
        'slug',
        'description',
        'image_path',
        'icon_path',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (Category $category): void {
            if ($category->isDirty('image_path')) {
                static::deletePublicFile($category->getOriginal('image_path'));
            }
            if ($category->isDirty('icon_path')) {
                static::deletePublicFile($category->getOriginal('icon_path'));
            }
        });

        static::deleting(function (Category $category): void {
            static::deletePublicFile($category->image_path);
            static::deletePublicFile($category->icon_path);
        });
    }

    public function products(): HasMany
    {
        return $this->hasMany(Product::class);
    }

    public function imageUrl(): ?string
    {
        return $this->publicUrl($this->image_path);
    }

    public function iconUrl(): ?string
    {
        return $this->publicUrl($this->icon_path) ?: $this->imageUrl();
    }
}
