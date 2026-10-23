<?php

namespace App\Filament\Admin\Widgets;

use App\Services\AnalyticsPeriod;
use Filament\Widgets\Widget;

class SalesPeriodWidget extends Widget
{
    protected static bool $isDiscovered = false;

    protected string $view = 'filament.admin.widgets.period';

    protected int|string|array $columnSpan = 'full';

    public function setPeriod(string $period): void
    {
        app(AnalyticsPeriod::class)->set($period);
        $this->redirect(request()->header('Referer', url('/admin')));
    }

    /**
     * @return array<int, string>
     */
    public function options(): array
    {
        return AnalyticsPeriod::OPTIONS;
    }

    public function current(): string
    {
        return app(AnalyticsPeriod::class)->key();
    }
}
