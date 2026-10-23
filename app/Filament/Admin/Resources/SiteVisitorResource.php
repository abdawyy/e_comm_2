<?php

namespace App\Filament\Admin\Resources;

use App\Exports\PageViewsExport;
use App\Exports\VisitorsExport;
use App\Filament\Admin\Resources\SiteVisitors\Pages\ListSiteVisitors;
use App\Filament\Admin\Resources\SiteVisitors\Pages\ViewSiteVisitor;
use App\Models\SiteVisitor;
use Filament\Actions\Action;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class SiteVisitorResource extends Resource
{
    protected static ?string $model = SiteVisitor::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedEye;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.analytics');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.visitors.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.visitors.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('device_type')->label(__('admin.fields.device')),
            TextColumn::make('country')->label(__('admin.fields.country')),
            TextColumn::make('region')->label(__('admin.fields.region')),
            TextColumn::make('area')->label(__('admin.customers.place')),
            TextColumn::make('city')->label(__('admin.fields.city')),
            TextColumn::make('referrer_host')->label(__('admin.fields.referrer')),
            TextColumn::make('last_seen_at')->dateTime()->label(__('admin.fields.last_seen')),
        ])->headerActions([
            Action::make('exportVisitors')->label(__('admin.actions.export_visitors'))->action(fn () => Excel::download(new VisitorsExport, 'visitors.xlsx')),
            Action::make('exportViews')->label(__('admin.actions.export_views'))->action(fn () => Excel::download(new PageViewsExport, 'page-views.xlsx')),
        ])->recordActions([
            ViewAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListSiteVisitors::route('/'),
            'view' => ViewSiteVisitor::route('/{record}'),
        ];
    }
}
