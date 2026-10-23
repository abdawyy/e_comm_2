<?php

namespace App\Models\Concerns;

use Illuminate\Support\Str;

trait HasLocalizedSlug
{
    public static function bootHasLocalizedSlug(): void
    {
        static::saving(function ($model): void {
            foreach (['en', 'ar'] as $locale) {
                $name = $model->getTranslation('name', $locale, false);
                $slug = $model->getTranslation('slug', $locale, false);

                if (filled($name) && blank($slug)) {
                    $model->setTranslation('slug', $locale, static::slugify((string) $name));
                }
            }
        });
    }

    public static function slugify(string $value): string
    {
        $slug = Str::slug($value);

        if ($slug === '') {
            $slug = trim((string) preg_replace('/[^\p{L}\p{N}]+/u', '-', $value), '-');
            $slug = mb_strtolower($slug);
        }

        return $slug !== '' ? $slug : Str::lower(Str::random(8));
    }

    public function getRouteKey(): mixed
    {
        $slug = $this->getTranslation('slug', app()->getLocale(), false);
        $slug = filled($slug) ? $slug : $this->getTranslation('slug', 'en', false);

        return filled($slug) ? $slug : $this->getKey();
    }

    public function resolveRouteBinding($value, $field = null)
    {
        return $this->resolveRouteBindingQuery($this->newQuery(), $value, $field)->first();
    }

    public function resolveRouteBindingQuery($query, $value, $field = null)
    {
        if ($field !== null && $field !== $this->getRouteKeyName()) {
            return $query->where($field, $value);
        }

        return $query->where(function ($inner) use ($value): void {
            $inner->where('slug->en', $value)->orWhere('slug->ar', $value);

            if (ctype_digit((string) $value)) {
                $inner->orWhere($this->getQualifiedKeyName(), $value);
            }
        });
    }
}
