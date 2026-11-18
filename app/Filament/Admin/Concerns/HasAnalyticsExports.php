<?php

namespace App\Filament\Admin\Concerns;

use Filament\Actions\Action;

trait HasAnalyticsExports
{
    /**
     * @return array<int, Action>
     */
    protected function analyticsExportActions(string $report): array
    {
        return [
            Action::make('exportExcel')
                ->label(__('admin.actions.export_excel'))
                ->url(route('admin.analytics.export', ['report' => $report, 'format' => 'xlsx'])),
            Action::make('exportPdf')
                ->label(__('admin.actions.export_pdf'))
                ->url(route('admin.analytics.export', ['report' => $report, 'format' => 'pdf'])),
            Action::make('exportAllExcel')
                ->label(__('admin.actions.export_all_excel'))
                ->url(route('admin.analytics.export', ['report' => 'all', 'format' => 'xlsx'])),
            Action::make('exportAllPdf')
                ->label(__('admin.actions.export_all_pdf'))
                ->url(route('admin.analytics.export', ['report' => 'all', 'format' => 'pdf'])),
        ];
    }
}
