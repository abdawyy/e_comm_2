<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Concerns\HasAnalyticsExports;
use App\Filament\Admin\Concerns\HasTrafficFilters;
use Filament\Pages\Page;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class TrafficDashboard extends Page
{
    use HasAnalyticsExports;
    use HasTrafficFilters;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedChartBar;

    protected static ?string $slug = 'traffic';

    protected static ?int $navigationSort = 2;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.analytics');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.traffic');
    }

    public function getTitle(): string
    {
        return __('admin.pages.traffic');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('View:TrafficDashboard');
    }

    protected function getHeaderActions(): array
    {
        return $this->analyticsExportActions('traffic');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            View::make('filament.admin.traffic')->viewData(fn () => $this->boardData()),
        ]);
    }
}
