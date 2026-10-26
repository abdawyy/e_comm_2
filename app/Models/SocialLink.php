<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Builder;
use Illuminate\Database\Eloquent\Collection;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Schema;
use Throwable;

class SocialLink extends Model
{
    protected $fillable = [
        'platform',
        'url',
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

    public function scopeForFooter(Builder $query): Builder
    {
        return $query->where('is_active', true)->orderBy('sort_order')->orderBy('id');
    }

    /**
     * @return Collection<int, self>
     */
    public static function footerLinks(): Collection
    {
        try {
            if (! Schema::hasTable('social_links')) {
                return new Collection;
            }
        } catch (Throwable) {
            return new Collection;
        }

        return static::query()->forFooter()->get()->filter(fn (self $link) => filled($link->safeUrl()))->values();
    }

    /**
     * @return array<string, string>
     */
    public static function platforms(): array
    {
        return [
            'facebook' => __('store.social.facebook'),
            'instagram' => __('store.social.instagram'),
            'tiktok' => __('store.social.tiktok'),
            'youtube' => __('store.social.youtube'),
            'whatsapp' => __('store.social.whatsapp'),
            'x' => __('store.social.x'),
            'snapchat' => __('store.social.snapchat'),
            'linkedin' => __('store.social.linkedin'),
        ];
    }

    public function label(): string
    {
        return static::platforms()[$this->platform] ?? $this->platform;
    }

    public function safeUrl(): ?string
    {
        $url = trim((string) $this->url);

        if ($url === '' || filter_var($url, FILTER_VALIDATE_URL) === false) {
            return null;
        }

        $scheme = strtolower((string) parse_url($url, PHP_URL_SCHEME));

        return in_array($scheme, ['http', 'https'], true) ? $url : null;
    }

    public function iconSvg(): string
    {
        return match ($this->platform) {
            'facebook' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M14.5 8.5V6.8c0-.7.5-1 1.2-1H17V3h-2.2C12.4 3 11 4.5 11 6.7v1.8H9v2.8h2V21h3v-9.7h2.3l.4-2.8h-2.7z"/></svg>',
            'instagram' => '<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8"><rect x="4" y="4" width="16" height="16" rx="4"/><circle cx="12" cy="12" r="3.4"/><circle cx="17.2" cy="6.8" r=".8" fill="currentColor" stroke="none"/></svg>',
            'tiktok' => '<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><path d="M14 6c.6 2.4 2.2 3.8 4.5 4v2.4c-1.6 0-3-.5-4.5-1.4V16a5 5 0 1 1-5-5c.3 0 .7 0 1 .1V13a2.6 2.6 0 1 0 1.8 2.5V6H14z"/></svg>',
            'youtube' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M21 8.2a3 3 0 0 0-2.1-2.1C17.2 5.6 12 5.6 12 5.6s-5.2 0-6.9.5A3 3 0 0 0 3 8.2 31 31 0 0 0 2.5 12a31 31 0 0 0 .5 3.8 3 3 0 0 0 2.1 2.1c1.7.5 6.9.5 6.9.5s5.2 0 6.9-.5a3 3 0 0 0 2.1-2.1 31 31 0 0 0 .5-3.8 31 31 0 0 0-.5-3.8zM10.4 15.2V8.8L15.5 12l-5.1 3.2z"/></svg>',
            'whatsapp' => '<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6.5 18.5 5 21l2.7-1.2A8 8 0 1 0 6.5 18.5z"/><path d="M9.2 9.4c.2-.4.4-.4.7-.4h.5c.2 0 .4 0 .5.4.2.5.6 1.6.6 1.7s0 .4-.2.6l-.3.4c-.1.2-.3.3-.1.6.2.3.7 1.1 1.5 1.5.6.3.8.2 1-.1l.4-.5c.2-.2.4-.2.6-.1.3.1 1.6.8 1.8.9.2.1.3.2.3.4.1.5-.4 1.4-1.1 1.7-.6.3-1.4.3-2.3 0-2.2-.7-4-3.2-4.2-3.5-.2-.3-1.1-1.5-1.1-2.8 0-1.2.7-1.8 1-2.2z"/></svg>',
            'x' => '<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M6 6l12 12M18 6 6 18"/></svg>',
            'snapchat' => '<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8" stroke-linejoin="round"><path d="M12 4.5a4.2 4.2 0 0 0-4.2 4.4v1.2c0 .5-.3.8-.7.9-.6.2-1.1.6-1.1 1.2 0 .8 1.3 1.1 1.8 1.2.3 0 .4.3.3.6-.2.6-.8 1.1-1.6 1.3 1 .5 1.8.3 2.4.8.5.4.8.8 1.5.8.8 0 1.1-.6 1.6-.6s.8.6 1.6.6c.7 0 1-.4 1.5-.8.6-.5 1.4-.3 2.4-.8-.8-.2-1.4-.7-1.6-1.3-.1-.3 0-.6.3-.6.5-.1 1.8-.4 1.8-1.2 0-.6-.5-1-1.1-1.2-.4-.1-.7-.4-.7-.9V8.9A4.2 4.2 0 0 0 12 4.5z"/></svg>',
            'linkedin' => '<svg viewBox="0 0 24 24" aria-hidden="true"><path fill="currentColor" d="M6.5 9H4v11h2.5V9zM5.2 4A1.6 1.6 0 1 0 5.2 7.2 1.6 1.6 0 0 0 5.2 4zM20 13.4c0-2.6-1.4-4.4-4-4.4-1.1 0-2.2.6-2.8 1.6h-.1V9H10.6c0 1.3 0 11 0 11H13v-6.1c0-.3 0-.7.1-1 .3-.7.9-1.5 2-1.5 1.4 0 2 1.1 2 2.6V20H20v-6.6z"/></svg>',
            default => '<svg viewBox="0 0 24 24" aria-hidden="true" fill="none" stroke="currentColor" stroke-width="1.8"><path d="M5 12h14M13 6l6 6-6 6"/></svg>',
        };
    }
}
