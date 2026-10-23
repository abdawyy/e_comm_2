<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Services\Pages\CreatePreparationService;
use App\Filament\Admin\Resources\Services\Pages\EditPreparationService;
use App\Filament\Admin\Resources\Services\Pages\ListServices;
use App\Filament\Admin\Support\Fields;
use App\Models\PreparationService;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PreparationServiceResource extends Resource
{
    protected static ?string $model = PreparationService::class;

    protected static ?string $slug = 'services';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedWrenchScrewdriver;

    public static function translatableFields(): array
    {
        return ['name', 'description'];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.catalog');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.services.plural');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.services.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.services.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            ...Fields::translated('name', __('admin.fields.name'), true),
            ...Fields::translated('description', __('admin.fields.description'), false, true),
            Select::make('surcharge_type')->options(['fixed' => __('admin.fields.fixed'), 'percent' => __('admin.fields.percent')])->required()->label(__('admin.fields.type')),
            TextInput::make('surcharge_value')->required()->label(__('admin.fields.value'))->rule('regex:/^\d+(\.\d{1,4})?$/'),
            Toggle::make('is_active')->default(true)->label(__('admin.fields.active')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label(__('admin.fields.name')),
            TextColumn::make('surcharge_type')->label(__('admin.fields.type')),
            TextColumn::make('surcharge_value')->label(__('admin.fields.value')),
        ])->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListServices::route('/'),
            'create' => CreatePreparationService::route('/create'),
            'edit' => EditPreparationService::route('/{record}/edit'),
        ];
    }
}
