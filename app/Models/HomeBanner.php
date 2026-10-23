<?php

namespace App\Models;

use App\Models\Concerns\HasPublicImage;
use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class HomeBanner extends Model
{
    use HasPublicImage, HasTranslations;

    public array $translatable = ['title', 'subtitle', 'badge'];

    protected $fillable = [
        'title',
        'subtitle',
        'badge',
        'image_path',
        'image_url',
        'gradient_start',
        'gradient_end',
        'buttons',
        'starts_at',
        'ends_at',
        'sort_order',
        'is_active',
    ];

    protected function casts(): array
    {
        return [
            'buttons' => 'array',
            'starts_at' => 'datetime',
            'ends_at' => 'datetime',
            'is_active' => 'boolean',
            'sort_order' => 'integer',
        ];
    }

    protected static function booted(): void
    {
        static::updating(function (HomeBanner $banner): void {
            if ($banner->isDirty('image_path')) {
                static::deletePublicFile($banner->getOriginal('image_path'));
            }
        });

        static::deleting(function (HomeBanner $banner): void {
            static::deletePublicFile($banner->image_path);
        });
    }

    public function scopeScheduled(Builder $query): Builder
    {
        $now = now();

        return $query->where('is_active', true)
            ->where(function (Builder $inner) use ($now) {
                $inner->whereNull('starts_at')->orWhere('starts_at', '<=', $now);
            })
            ->where(function (Builder $inner) use ($now) {
                $inner->whereNull('ends_at')->orWhere('ends_at', '>=', $now);
            })
            ->orderBy('sort_order');
    }

    public function imageUrl(): ?string
    {
        return $this->publicUrl($this->image_path, $this->image_url);
    }

    /**
     * @return array<int, array{label: string, url: string}>
     */
    public function localizedButtons(): array
    {
        $locale = app()->getLocale();
        $buttons = [];

        foreach ($this->buttons ?? [] as $button) {
            $label = $button['label'][$locale] ?? $button['label']['en'] ?? ($button['label'] ?? '');

            if (is_array($label)) {
                $label = $label[$locale] ?? $label['en'] ?? '';
            }

            if (filled($label) && filled($button['url'] ?? null)) {
                $buttons[] = ['label' => (string) $label, 'url' => (string) $button['url']];
            }
        }

        return $buttons;
    }
}
