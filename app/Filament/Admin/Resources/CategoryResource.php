<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Categories\Pages\CreateCategory;
use App\Filament\Admin\Resources\Categories\Pages\EditCategory;
use App\Filament\Admin\Resources\Categories\Pages\ListCategories;
use App\Filament\Admin\Support\Columns;
use App\Filament\Admin\Support\Fields;
use App\Models\Category;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class CategoryResource extends Resource
{
    protected static ?string $model = Category::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedRectangleStack;

    protected static ?int $navigationSort = 10;

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
        return __('admin.resources.categories.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.categories.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('admin.forms.details'))->description(__('admin.forms.details_help'))->icon(Heroicon::OutlinedLanguage)->schema([
                ...Fields::translated('name', __('admin.fields.name'), true),
                ...Fields::translated('description', __('admin.fields.description'), false, true),
            ])->columns(2),
            Section::make(__('admin.forms.media'))->description(__('admin.forms.media_help'))->icon(Heroicon::OutlinedPhoto)->schema([
                FileUpload::make('image_path')->label(__('admin.fields.image'))->disk('public')->directory('categories')->image()->imagePreviewHeight('180')->maxSize(4096)->helperText(__('admin.help.category_image'))->columnSpanFull(),
            ]),
            Section::make(__('admin.forms.publish'))->icon(Heroicon::OutlinedEye)->schema([
                TextInput::make('sort_order')->numeric()->default(0)->label(__('admin.fields.sort')),
                Toggle::make('is_active')->default(true)->label(__('admin.fields.active'))->inline(false),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image_path')->disk('public')->label(__('admin.fields.image')),
            TextColumn::make('name')->label(__('admin.fields.name'))->searchable(),
            Columns::active(),
            TextColumn::make('sort_order')->label(__('admin.fields.sort')),
        ])->recordActions([
            EditAction::make(),
        ])->toolbarActions([
            BulkActionGroup::make([DeleteBulkAction::make()]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListCategories::route('/'),
            'create' => CreateCategory::route('/create'),
            'edit' => EditCategory::route('/{record}/edit'),
        ];
    }
}
