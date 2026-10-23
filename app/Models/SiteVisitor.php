<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Database\Eloquent\Relations\HasMany;

class SiteVisitor extends Model
{
    protected $fillable = [
        'session_id',
        'user_id',
        'ip_hash',
        'user_agent_hash',
        'device_type',
        'referrer',
        'referrer_host',
        'utm_source',
        'utm_medium',
        'utm_campaign',
        'utm_content',
        'utm_term',
        'country_code',
        'country',
        'region',
        'city',
        'area',
        'geo_resolved_at',
        'first_seen_at',
        'last_seen_at',
    ];

    protected function casts(): array
    {
        return [
            'geo_resolved_at' => 'datetime',
            'first_seen_at' => 'datetime',
            'last_seen_at' => 'datetime',
        ];
    }

    public function user(): BelongsTo
    {
        return $this->belongsTo(User::class);
    }

    public function pageViews(): HasMany
    {
        return $this->hasMany(SitePageView::class);
    }

    public function locationLabel(): string
    {
        return collect([$this->area, $this->city, $this->region, $this->country])->filter()->unique()->implode(', ');
    }
}
