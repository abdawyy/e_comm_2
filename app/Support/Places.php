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
}
