<?php

namespace App\Models;

use App\Models\Concerns\HasPublicImage;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class ProductImage extends Model
{
    use HasPublicImage;

    protected $fillable = [
        'product_id',
        'image_path',
        'sort_order',
    ];

    protected function casts(): array
    {
        return [
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (ProductImage $image): void {
            if ($image->isDirty('image_path')) {
                static::deletePublicFile($image->getOriginal('image_path'));
            }
        });

        static::deleting(function (ProductImage $image): void {
            static::deletePublicFile($image->image_path);
        });
    }

    public function product(): BelongsTo
    {
        return $this->belongsTo(Product::class);
    }

    public function url(): ?string
    {
        return $this->publicUrl($this->image_path);
    }
}
