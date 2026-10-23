<?php

namespace App\Models;

use App\Models\Concerns\HasLocalizedSlug;
use App\Models\Concerns\HasPublicImage;
use App\Services\DecimalMath;
use Database\Factories\ProductFactory;
use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\BelongsToMany;
use Illuminate\Database\Eloquent\Relations\HasMany;
use Spatie\Translatable\HasTranslations;

class Product extends Model
{
    /** @use HasFactory<ProductFactory> */
    use HasFactory, HasLocalizedSlug, HasPublicImage, HasTranslations;

    public array $translatable = ['name', 'slug', 'description'];

    protected $fillable = [
        'category_id',
        'sku',
        'name',
        'slug',
        'description',
        'price_per_unit',
        'unit',
        'secondary_unit',
        'secondary_price',
        'track_stock',
        'stock_quantity',
        'image_path',
        'image_url',
        'is_featured',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'price_per_unit' => 'decimal:4',
            'secondary_price' => 'decimal:4',
            'stock_quantity' => 'decimal:4',
            'track_stock' => 'boolean',
            'is_featured' => 'boolean',
            'is_active' => 'boolean',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (Product $product): void {
            if ($product->isDirty('image_path')) {
                static::deletePublicFile($product->getOriginal('image_path'));
            }
        });

        static::deleting(function (Product $product): void {
            static::deletePublicFile($product->image_path);
        });
    }

    public function category(): BelongsTo
    {
        return $this->belongsTo(Category::class);
    }

    public function images(): HasMany
    {
        return $this->hasMany(ProductImage::class)->orderBy('sort_order');
    }

    public function services(): BelongsToMany
    {
        return $this->belongsToMany(PreparationService::class, 'product_preparation_service');
    }

    public function packagingTypes(): BelongsToMany
    {
        return $this->belongsToMany(PackagingType::class, 'product_packaging_type');
    }

    public function priceForUnit(string $unit): ?string
    {
        if ($unit === $this->unit) {
            return app(DecimalMath::class)->normalize((string) $this->price_per_unit);
        }

        if ($this->secondary_unit && $unit === $this->secondary_unit && $this->secondary_price !== null) {
            return app(DecimalMath::class)->normalize((string) $this->secondary_price);
        }

        return null;
    }

    public function coverUrl(): ?string
    {
        if (filled($this->image_path) || filled($this->image_url)) {
            return $this->publicUrl($this->image_path, $this->image_url);
        }

        $image = $this->relationLoaded('images') ? $this->images->first() : $this->images()->first();

        return $image?->url();
    }
}
