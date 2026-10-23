<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Cities\Pages\CreateCity;
use App\Filament\Admin\Resources\Cities\Pages\EditCity;
use App\Filament\Admin\Resources\Cities\Pages\ListCities;
use App\Filament\Admin\Support\Fields;
use App\Models\City;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CityResource extends Resource
{
    protected static ?string $model = City::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    public static function translatableFields(): array
    {
        return ['name'];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.catalog');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.cities.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.cities.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            ...Fields::translated('name', __('admin.fields.name'), true),
            TextInput::make('shipping_fee')->required()->label(__('admin.fields.shipping_fee'))->rule('regex:/^\d+(\.\d{1,4})?$/'),
            TextInput::make('sort_order')->numeric()->default(0)->label(__('admin.fields.sort')),
            Toggle::make('is_active')->default(true)->label(__('admin.fields.active')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label(__('admin.fields.name')),
            TextColumn::make('shipping_fee')->label(__('admin.fields.shipping_fee')),
        ])->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCities::route('/'),
            'create' => CreateCity::route('/create'),
            'edit' => EditCity::route('/{record}/edit'),
        ];
    }
}
