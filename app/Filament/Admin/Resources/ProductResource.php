<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Products\Pages\CreateProduct;
use App\Filament\Admin\Resources\Products\Pages\EditProduct;
use App\Filament\Admin\Resources\Products\Pages\ListProducts;
use App\Filament\Admin\Support\Columns;
use App\Filament\Admin\Support\Fields;
use App\Exports\ProductsExport;
use App\Models\Product;
use Filament\Actions\BulkActionGroup;
use Filament\Actions\DeleteBulkAction;
use Filament\Actions\EditAction;
use Filament\Forms\Components\FileUpload;
use Filament\Forms\Components\Repeater;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Components\Tabs;
use Filament\Schemas\Components\Tabs\Tab;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Maatwebsite\Excel\Facades\Excel;

class ProductResource extends Resource
{
    protected static ?string $model = Product::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedShoppingBag;

    protected static ?int $navigationSort = 11;

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
        return __('admin.resources.products.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.products.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Tabs::make('product')->persistTabInQueryString()->tabs([
                Tab::make(__('admin.forms.details'))->icon(Heroicon::OutlinedDocumentText)->schema([
                    Section::make(__('admin.forms.details'))->description(__('admin.forms.details_help'))->icon(Heroicon::OutlinedLanguage)->schema([
                        TextInput::make('sku')->label(__('admin.fields.sku'))->required()->unique(ignoreRecord: true)->prefixIcon(Heroicon::OutlinedHashtag),
                        Select::make('category_id')->label(__('admin.resources.categories.label'))->relationship('category', 'name')->searchable()->preload()->native(false),
                        ...Fields::translated('name', __('admin.fields.name'), true),
                        ...Fields::translated('description', __('admin.fields.description'), false, true),
                    ])->columns(2),
                ]),
                Tab::make(__('admin.forms.price'))->icon(Heroicon::OutlinedBanknotes)->schema([
                    Section::make(__('admin.forms.price'))->description(__('admin.forms.price_help'))->icon(Heroicon::OutlinedBanknotes)->schema([
                        TextInput::make('price_per_unit')->label(__('admin.fields.price'))->required()->prefix(config('store.currency', 'EGP'))->rule('regex:/^\d+(\.\d{1,4})?$/'),
                        TextInput::make('unit')->label(__('admin.fields.unit'))->required()->default('piece')->placeholder('kg, piece, pack'),
                        TextInput::make('secondary_unit')->label(__('admin.fields.secondary_unit')),
                        TextInput::make('secondary_price')->label(__('admin.fields.secondary_price'))->prefix(config('store.currency', 'EGP'))->rule('nullable|regex:/^\d+(\.\d{1,4})?$/'),
                        TextInput::make('stock_quantity')->label(__('admin.fields.stock'))->default('0')->rule('regex:/^\d+(\.\d{1,4})?$/'),
                        Toggle::make('track_stock')->label(__('admin.fields.track_stock'))->default(true)->inline(false),
                    ])->columns(2),
                    Section::make(__('admin.forms.publish'))->icon(Heroicon::OutlinedEye)->schema([
                        Toggle::make('is_featured')->label(__('admin.fields.featured'))->inline(false),
                        Toggle::make('is_active')->label(__('admin.fields.active'))->default(true)->inline(false),
                    ])->columns(2),
                ]),
                Tab::make(__('admin.forms.media'))->icon(Heroicon::OutlinedPhoto)->schema([
                    Section::make(__('admin.forms.media'))->description(__('admin.forms.media_help'))->icon(Heroicon::OutlinedPhoto)->schema([
                        FileUpload::make('image_path')->label(__('admin.fields.cover'))->disk('public')->directory('products')->image()->imagePreviewHeight('220')->maxSize(4096)->helperText(__('admin.help.product_image'))->columnSpanFull(),
                        TextInput::make('image_url')->label(__('admin.fields.image_url'))->url()->helperText(__('admin.help.image_url'))->columnSpanFull(),
                        Repeater::make('images')->relationship()->schema([
                            FileUpload::make('image_path')->label(__('admin.fields.image'))->disk('public')->directory('products/gallery')->image()->imagePreviewHeight('120')->maxSize(4096)->required(),
                            TextInput::make('sort_order')->numeric()->default(0)->label(__('admin.fields.sort')),
                        ])->orderColumn('sort_order')->label(__('admin.fields.gallery'))->grid(2)->columnSpanFull(),
                    ])->columns(2),
                ]),
                Tab::make(__('admin.forms.options'))->icon(Heroicon::OutlinedSquares2x2)->schema([
                    Section::make(__('admin.forms.options'))->description(__('admin.forms.options_help'))->icon(Heroicon::OutlinedGift)->schema([
                        Select::make('services')->label(__('admin.resources.services.plural'))->relationship('services', 'name')->multiple()->preload()->native(false),
                        Select::make('packagingTypes')->label(__('admin.resources.packaging.plural'))->relationship('packagingTypes', 'name')->multiple()->preload()->native(false),
                    ])->columns(2),
                ]),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('sku')->searchable()->label(__('admin.fields.sku')),
            TextColumn::make('name')->searchable()->label(__('admin.fields.name')),
            TextColumn::make('price_per_unit')->label(__('admin.fields.price')),
            TextColumn::make('unit')->label(__('admin.fields.unit')),
            TextColumn::make('stock_quantity')->label(__('admin.fields.stock')),
            Columns::active(),
        ])->headerActions([
            \Filament\Actions\Action::make('export')->label(__('admin.actions.export'))->action(fn () => Excel::download(new ProductsExport, 'products.xlsx')),
        ])->recordActions([
            EditAction::make(),
        ])->toolbarActions([
            BulkActionGroup::make([DeleteBulkAction::make()]),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProducts::route('/'),
            'create' => CreateProduct::route('/create'),
            'edit' => EditProduct::route('/{record}/edit'),
        ];
    }
}
