<?php

namespace App\Support;

class Places
{
    /**
     * Neighborhoods and districts a visitor or delivery address can be matched to.
     *
     * @var array<string, array<int, string>>
     */
    public const AREAS = [
        'Sheraton' => ['sheraton', 'شيراتون', 'الشيراتون'],
        'Sheikh Zayed' => ['sheikh zayed', 'el sheikh zayed', 'الشيخ زايد'],
        '6th of October' => ['6th of october', '6 october', 'sixth of october', 'السادس من أكتوبر', '6 أكتوبر'],
        'New Cairo' => ['new cairo', 'القاهرة الجديدة'],
        'Fifth Settlement' => ['fifth settlement', '5th settlement', 'التجمع الخامس', 'التجمع'],
        'Nasr City' => ['nasr city', 'مدينة نصر'],
        'Heliopolis' => ['heliopolis', 'مصر الجديدة'],
        'Maadi' => ['maadi', 'المعادي'],
        'Zamalek' => ['zamalek', 'الزمالك'],
        'Dokki' => ['dokki', 'الدقي'],
        'Mohandessin' => ['mohandessin', 'mohandeseen', 'المهندسين'],
        'Rehab' => ['rehab', 'الرحاب'],
        'Madinaty' => ['madinaty', 'مدينتي'],
        'Downtown Cairo' => ['downtown', 'وسط البلد'],
        'Giza' => ['giza', 'الجيزة'],
        'Alexandria' => ['alexandria', 'الإسكندرية'],
    ];

    public function match(?string ...$parts): ?string
    {
        $haystack = mb_strtolower(trim(implode(' ', array_filter($parts, fn ($part) => filled($part)))));

        if ($haystack === '') {
            return null;
        }

        foreach (self::AREAS as $label => $needles) {
            foreach ($needles as $needle) {
                if (str_contains($haystack, mb_strtolower($needle))) {
                    return $label;
                }
            }
        }

        return null;
    }

    public function label(?string $country, ?string $city, ?string $place): string
    {
        $parts = [];

        foreach ([$country, $city, $place] as $part) {
            $part = trim((string) $part);

            if ($part === '') {
                continue;
            }

            $key = mb_strtolower($part);

            if (in_array($key, array_map(mb_strtolower(...), $parts), true)) {
                continue;
            }

            $parts[] = $part;
        }

        return implode(', ', $parts);
    }

    public function orderLabel(?string $city, ?string $address): string
    {
        $cityName = filled($city) ? trim((string) $city) : null;
        $place = $this->match($address, $cityName);

        if ($place !== null && $cityName !== null && mb_strtolower($place) === mb_strtolower($cityName)) {
            $place = $this->match($address);
        }

        if ($place !== null && $cityName !== null && mb_strtolower($place) === mb_strtolower($cityName)) {
            $place = null;
        }

        $country = $this->egyptian($cityName, $address, $place) ? 'Egypt' : null;

        return $this->label($country, $cityName, $place);
    }

    private function egyptian(?string ...$parts): bool
    {
        $haystack = mb_strtolower(trim(implode(' ', array_filter($parts, fn ($part) => filled($part)))));

        if ($haystack === '') {
            return false;
        }

        if (str_contains($haystack, 'egypt') || str_contains($haystack, 'مصر')) {
            return true;
        }

        if ($this->match($haystack) !== null) {
            return true;
        }

        foreach (['cairo', 'giza', 'alexandria', 'القاهرة', 'الجيزة', 'الإسكندرية', 'اسكندرية'] as $needle) {
            if (str_contains($haystack, $needle)) {
                return true;
            }
        }

        return false;
    }
}
