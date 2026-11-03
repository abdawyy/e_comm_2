<?php

namespace App\Filament\Admin\Widgets;

class ChartPalette
{
    /** @var array<int, string> */
    public const COLORS = [
        '#1A2B28',
        '#C9A55C',
        '#2F4F47',
        '#8C6A3D',
        '#6B8F71',
        '#D4534A',
        '#3D6B8C',
        '#E7C98A',
    ];

    /** @var array<string, string> */
    public const STATUS = [
        'pending' => '#C9A55C',
        'confirmed' => '#3D6B8C',
        'processing' => '#2F4F47',
        'shipped' => '#6B8F71',
        'delivered' => '#1A2B28',
        'cancelled' => '#D4534A',
    ];
}
