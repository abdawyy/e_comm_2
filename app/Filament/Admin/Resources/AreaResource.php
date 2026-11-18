<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Areas\Pages\CreateArea;
use App\Filament\Admin\Resources\Areas\Pages\EditArea;
use App\Filament\Admin\Resources\Areas\Pages\ListAreas;
use App\Filament\Admin\Support\Columns;
use App\Filament\Admin\Support\Fields;
use App\Models\Area;
use App\Models\City;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Filters\SelectFilter;
use Filament\Tables\Table;
use Illuminate\Support\Str;

class AreaResource extends Resource
{
    protected static ?string $model = Area::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedMapPin;

    protected static ?int $navigationSort = 2;

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
        return __('admin.resources.areas.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.areas.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Select::make('city_id')->label(__('admin.fields.governorate'))->options(fn () => City::query()->orderBy('sort_order')->get()->mapWithKeys(fn (City $city) => [$city->id => $city->name]))->searchable()->required()->native(false),
            ...Fields::translated('name', __('admin.customers.place'), true),
            TextInput::make('shipping_fee')->required()->label(__('admin.fields.shipping_fee'))->rule('regex:/^\d+(\.\d{1,4})?$/'),
            TextInput::make('sort_order')->numeric()->default(0)->label(__('admin.fields.sort')),
            Toggle::make('is_active')->onColor('success')->offColor('gray')->default(true)->label(__('admin.fields.active')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('city.name')->label(__('admin.fields.governorate')),
            TextColumn::make('name')->label(__('admin.customers.place')),
            TextColumn::make('shipping_fee')->label(__('admin.fields.shipping_fee')),
            Columns::active(),
        ])->filters([
            SelectFilter::make('city_id')->label(__('admin.fields.governorate'))->options(fn () => City::query()->orderBy('sort_order')->get()->mapWithKeys(fn (City $city) => [$city->id => $city->name])),
        ])->recordActions([EditAction::make()]);
    }

    public static function mutateKey(array $data): array
    {
        if (blank($data['key'] ?? null) && filled($data['name']['en'] ?? null)) {
            $data['key'] = Str::slug((string) $data['city_id'].'-'.$data['name']['en']);
        }

        return $data;
    }

    public static function getPages(): array
    {
        return [
            'index' => ListAreas::route('/'),
            'create' => CreateArea::route('/create'),
            'edit' => EditArea::route('/{record}/edit'),
        ];
    }
}
