<?php

namespace App\Support;

class Phone
{
    public static function normalize(?string $phone): string
    {
        $digits = preg_replace('/\D+/', '', (string) $phone) ?? '';

        if (str_starts_with($digits, '20') && strlen($digits) >= 12) {
            $digits = '0'.substr($digits, 2);
        }

        return $digits;
    }

    public static function e164(?string $phone): string
    {
        $normalized = self::normalize($phone);

        if ($normalized === '') {
            return '+200000000000';
        }

        if (str_starts_with($normalized, '0')) {
            return '+2'.$normalized;
        }

        return '+'.$normalized;
    }

    public static function same(string $left, string $right): bool
    {
        if ($left === '' || $right === '' || strlen($left) !== strlen($right)) {
            return false;
        }

        return hash_equals($left, $right);
    }
}
