<?php

namespace App\Exports;

use App\Models\SitePageView;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\WithHeadings;

class PageViewsExport implements FromCollection, WithHeadings
{
    public function collection()
    {
        return SitePageView::query()->latest('viewed_at')->limit(5000)->get()->map(fn (SitePageView $view) => [
            'id' => $view->id,
            'visitor_id' => $view->site_visitor_id,
            'path' => $view->path,
            'viewed_at' => optional($view->viewed_at)->toDateTimeString(),
        ]);
    }

    public function headings(): array
    {
        return ['ID', 'Visitor', 'Path', 'Viewed at'];
    }
}
