<?php

namespace App\Exports;

use App\Models\SiteVisitor;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class VisitorsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return SiteVisitor::query()->orderByDesc('last_seen_at')->get()->map(fn (SiteVisitor $visitor) => [
            'id' => $visitor->id,
            'device' => $visitor->device_type,
            'country' => $visitor->country,
            'region' => $visitor->region,
            'city' => $visitor->city,
            'area' => $visitor->area,
            'referrer' => $visitor->referrer_host,
            'utm_source' => $visitor->utm_source,
            'first_seen_at' => optional($visitor->first_seen_at)->toDateTimeString(),
            'last_seen_at' => optional($visitor->last_seen_at)->toDateTimeString(),
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Device', 'Country', 'Region', 'City', 'Area', 'Referrer', 'UTM source', 'First seen', 'Last seen'];
    }
}
