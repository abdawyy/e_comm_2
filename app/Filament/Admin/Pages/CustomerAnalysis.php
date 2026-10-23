<?php

namespace App\Filament\Admin\Pages;

use App\Filament\Admin\Concerns\HasTrafficFilters;
use App\Services\CustomerDirectory;
use Filament\Pages\Page;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class CustomerAnalysis extends Page
{
    use HasTrafficFilters;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedUserGroup;

    protected static ?string $slug = 'customer-analysis';

    protected static ?int $navigationSort = 1;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.customers');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.customers');
    }

    public function getTitle(): string
    {
        return __('admin.pages.customers');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('ViewAny:User');
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            View::make('filament.admin.customer-analysis')->viewData(fn () => [
                ...app(CustomerDirectory::class)->report(),
                ...$this->boardData(),
            ]),
        ]);
    }
}
