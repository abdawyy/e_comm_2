<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromArray;
use Maatwebsite\Excel\Concerns\WithHeadings;
use Maatwebsite\Excel\Concerns\WithTitle;

class AnalyticsSheetExport implements FromArray, WithHeadings, WithTitle
{
    /**
     * @param  array<int, array{title: string, rows: array<int, array{label: string, value: string}>}>  $sections
     */
    public function __construct(private array $sections, private string $title = 'Analytics') {}

    public function array(): array
    {
        $rows = [];

        foreach ($this->sections as $section) {
            foreach ($section['rows'] as $row) {
                $rows[] = [$section['title'], $row['label'], $row['value']];
            }
        }

        return $rows;
    }

    public function headings(): array
    {
        return ['Section', 'Label', 'Value'];
    }

    public function title(): string
    {
        return $this->title;
    }
}
