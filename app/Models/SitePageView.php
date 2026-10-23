<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Relations\BelongsTo;

class SitePageView extends Model
{
    public $timestamps = false;

    protected $fillable = [
        'site_visitor_id',
        'path',
        'viewed_at',
    ];

    protected function casts(): array
    {
        return [
            'viewed_at' => 'datetime',
        ];
    }

    public function visitor(): BelongsTo
    {
        return $this->belongsTo(SiteVisitor::class, 'site_visitor_id');
    }
}
