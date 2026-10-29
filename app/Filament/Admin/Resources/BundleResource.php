<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Bundles\Pages\CreateBundle;
use App\Filament\Admin\Resources\Bundles\Pages\EditBundle;
use App\Filament\Admin\Resources\Bundles\Pages\ListBundles;
use App\Filament\Admin\Support\Columns;
use App\Filament\Admin\Support\Fields;
use App\Models\Bundle;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\ImageColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class BundleResource extends Resource
{
    protected static ?string $model = Bundle::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedGift;

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
        return __('admin.resources.bundles.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.bundles.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('admin.forms.details'))->description(__('admin.forms.details_help'))->icon(Heroicon::OutlinedLanguage)->schema([
                ...Fields::translated('name', __('admin.fields.name'), true),
                ...Fields::translated('description', __('admin.fields.description'), false, true),
            ])->columns(2),
            Section::make(__('admin.forms.price'))->icon(Heroicon::OutlinedBanknotes)->schema([
                TextInput::make('price')->required()->label(__('admin.fields.price'))->prefix(config('store.currency', 'EGP'))->rule('regex:/^\d+(\.\d{1,4})?$/'),
                Toggle::make('is_subscribable')->label(__('admin.fields.subscribable'))->inline(false),
                Toggle::make('is_active')->onColor('success')->offColor('gray')->default(true)->label(__('admin.fields.active'))->inline(false),
            ])->columns(3),
            Section::make(__('admin.forms.media'))->description(__('admin.help.bundle_image'))->icon(Heroicon::OutlinedPhoto)->schema([
                FileUpload::make('image_path')
                    ->label(__('admin.fields.cover'))
                    ->disk('public')
                    ->directory('bundles')
                    ->visibility('public')
                    ->image()
                    ->imagePreviewHeight('220')
                    ->maxSize(4096)
                    ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                    ->helperText(__('admin.help.bundle_image'))
                    ->columnSpanFull(),
                TextInput::make('image_url')->label(__('admin.fields.image_url'))->url()->helperText(__('admin.help.image_url'))->columnSpanFull(),
                Repeater::make('images')->relationship()->schema([
                    FileUpload::make('image_path')
                        ->label(__('admin.fields.image'))
                        ->disk('public')
                        ->directory('bundles/gallery')
                        ->visibility('public')
                        ->image()
                        ->imagePreviewHeight('120')
                        ->maxSize(4096)
                        ->acceptedFileTypes(['image/jpeg', 'image/png', 'image/webp', 'image/svg+xml'])
                        ->required(),
                    TextInput::make('sort_order')->numeric()->default(0)->label(__('admin.fields.sort')),
                ])->orderColumn('sort_order')->label(__('admin.fields.gallery'))->addActionLabel(__('admin.fields.image'))->grid(2)->columnSpanFull(),
            ])->columns(2),
            Section::make(__('admin.forms.contents'))->icon(Heroicon::OutlinedRectangleStack)->schema([
                Repeater::make('items')->relationship()->schema([
                    Select::make('product_id')->relationship('product', 'name')->required()->searchable()->preload()->native(false)->label(__('admin.resources.products.label')),
                    TextInput::make('quantity')->required()->default('1')->label(__('admin.fields.quantity')),
                ])->grid(2)->columnSpanFull()->label(__('admin.fields.items')),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            ImageColumn::make('image_path')->disk('public')->label(__('admin.fields.image')),
            TextColumn::make('name')->label(__('admin.fields.name')),
            TextColumn::make('price')->label(__('admin.fields.price')),
            IconColumn::make('is_subscribable')->boolean()->label(__('admin.fields.subscribable')),
            Columns::active(),
        ])->recordActions([EditAction::make()]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListBundles::route('/'),
            'create' => CreateBundle::route('/create'),
            'edit' => EditBundle::route('/{record}/edit'),
        ];
    }
}
