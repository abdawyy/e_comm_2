<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Packaging\Pages\CreatePackagingType;
use App\Filament\Admin\Resources\Packaging\Pages\EditPackagingType;
use App\Filament\Admin\Resources\Packaging\Pages\ListPackaging;
use App\Filament\Admin\Support\Columns;
use App\Filament\Admin\Support\Fields;
use App\Models\PackagingType;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PackagingTypeResource extends Resource
{
    protected static ?string $model = PackagingType::class;

    protected static ?string $slug = 'packaging';

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCube;

    public static function translatableFields(): array
    {
        return ['name', 'description'];
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.catalog');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.packaging.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.packaging.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            ...Fields::translated('name', __('admin.fields.name'), true),
            ...Fields::translated('description', __('admin.fields.description'), false, true),
            Select::make('surcharge_type')->options(['fixed' => __('admin.fields.fixed'), 'percent' => __('admin.fields.percent')])->required(),
            TextInput::make('surcharge_value')->required()->rule('regex:/^\d+(\.\d{1,4})?$/'),
            Select::make('scope')->options(['line' => __('admin.fields.line'), 'order' => __('admin.fields.order')])->default('line')->required()->label(__('admin.fields.scope')),
            Toggle::make('is_active')->onColor('success')->offColor('gray')->default(true),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('name'),
            TextColumn::make('scope'),
            TextColumn::make('surcharge_value'),
            Columns::active(),
        ])->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPackaging::route('/'),
            'create' => CreatePackagingType::route('/create'),
            'edit' => EditPackagingType::route('/{record}/edit'),
        ];
    }
}
