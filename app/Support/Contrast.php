<?php

namespace App\Support;

class Contrast
{
    public const MINIMUM_RATIO = 4.5;

    public static function isHex(string $color): bool
    {
        return (bool) preg_match('/^#[0-9A-Fa-f]{6}$/', $color);
    }

    public static function ratio(string $foreground, string $background): float
    {
        $lighter = max(self::luminance($foreground), self::luminance($background));
        $darker = min(self::luminance($foreground), self::luminance($background));

        return ($lighter + 0.05) / ($darker + 0.05);
    }

    public static function whiteOnPrimaryPasses(string $primary): bool
    {
        if (! self::isHex($primary)) {
            return false;
        }

        return self::ratio('#ffffff', $primary) >= self::MINIMUM_RATIO;
    }

    public static function luminance(string $hex): float
    {
        $hex = ltrim($hex, '#');

        if (strlen($hex) !== 6) {
            return 0.0;
        }

        $channels = [
            hexdec(substr($hex, 0, 2)) / 255,
            hexdec(substr($hex, 2, 2)) / 255,
            hexdec(substr($hex, 4, 2)) / 255,
        ];

        $linear = array_map(function (float $channel): float {
            if ($channel <= 0.03928) {
                return $channel / 12.92;
            }

            return (($channel + 0.055) / 1.055) ** 2.4;
        }, $channels);

        return (0.2126 * $linear[0]) + (0.7152 * $linear[1]) + (0.0722 * $linear[2]);
    }
}
