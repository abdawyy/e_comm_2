<?php

namespace App\Filament\Admin\Pages;

use Filament\Actions\Action;
use Filament\Pages\Page;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class AdminGuide extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedBookOpen;

    protected static ?string $slug = 'guide';

    protected static ?int $navigationSort = 90;

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.guide');
    }

    public function getTitle(): string
    {
        return __('admin.pages.guide');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('View:AdminGuide');
    }

    protected function getHeaderActions(): array
    {
        return [
            Action::make('download')
                ->label(__('admin.guide.download'))
                ->url(route('admin.guide.pdf'))
                ->openUrlInNewTab(),
        ];
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            View::make('filament.admin.guide'),
        ]);
    }
}
