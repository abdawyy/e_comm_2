<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ShippingCarriers\Pages\CreateShippingCarrier;
use App\Filament\Admin\Resources\ShippingCarriers\Pages\EditShippingCarrier;
use App\Filament\Admin\Resources\ShippingCarriers\Pages\ListShippingCarriers;
use App\Filament\Admin\Support\Columns;
use App\Filament\Admin\Support\Fields;
use App\Models\ShippingCarrier;
use Filament\Actions\EditAction;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ShippingCarrierResource extends Resource
{
    protected static ?string $model = ShippingCarrier::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?int $navigationSort = 4;

    public static function translatableFields(): array
    {
        return ['name'];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.delivery');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.carriers.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.carriers.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            TextInput::make('code')->label(__('admin.fields.code'))->required()->maxLength(40)->unique(ignoreRecord: true),
            ...Fields::translated('name', __('admin.fields.name'), true),
            TextInput::make('fee')->label(__('store.delivery.fee'))->rule('nullable|regex:/^\d+(\.\d{1,4})?$/')->helperText(__('store.delivery.fee_help')),
            TextInput::make('sort_order')->numeric()->default(0)->label(__('admin.fields.sort')),
            Toggle::make('is_active')->onColor('success')->offColor('gray')->default(true)->label(__('admin.fields.active')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name')->label(__('admin.fields.name')),
            TextColumn::make('code')->label(__('admin.fields.code')),
            TextColumn::make('fee')->label(__('store.delivery.fee'))->placeholder('—'),
            Columns::active(),
        ])->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListShippingCarriers::route('/'),
            'create' => CreateShippingCarrier::route('/create'),
            'edit' => EditShippingCarrier::route('/{record}/edit'),
        ];
    }
}
