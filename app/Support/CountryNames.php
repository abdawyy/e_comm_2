<?php

namespace App\Support;

class CountryNames
{
    public function name(?string $value): ?string
    {
        $value = trim((string) $value);

        if ($value === '') {
            return null;
        }

        if (preg_match('/^[A-Za-z]{2}$/', $value) === 1) {
            $code = strtoupper($value);

            if (function_exists('locale_get_display_region')) {
                $label = locale_get_display_region('-'.$code, 'en');

                if (is_string($label) && $label !== '' && $label !== $code && ! str_starts_with($label, '-')) {
                    return $label;
                }
            }

            return match ($code) {
                'EG' => 'Egypt',
                'SA' => 'Saudi Arabia',
                'AE' => 'United Arab Emirates',
                'KW' => 'Kuwait',
                'QA' => 'Qatar',
                'BH' => 'Bahrain',
                'OM' => 'Oman',
                'JO' => 'Jordan',
                'LB' => 'Lebanon',
                default => $code,
            };
        }

        return $value;
    }
}
