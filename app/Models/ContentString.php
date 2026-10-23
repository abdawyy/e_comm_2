<?php

namespace App\Models;

use App\Support\Cms;
use Illuminate\Database\Eloquent\Model;
use Spatie\Translatable\HasTranslations;

class ContentString extends Model
{
    use HasTranslations;

    public array $translatable = ['value'];

    protected $fillable = [
        'key',
        'value',
    ];

    protected static function booted(): void
    {
        static::saved(fn () => Cms::flush());
        static::deleted(fn () => Cms::flush());
    }
}
