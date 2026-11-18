<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductInquiries\Pages\EditProductInquiry;
use App\Filament\Admin\Resources\ProductInquiries\Pages\ListProductInquiries;
use App\Models\ProductInquiry;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductInquiryResource extends Resource
{
    protected static ?string $model = ProductInquiry::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static ?int $navigationSort = 4;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.sales');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.resources.inquiries.plural');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.inquiries.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.inquiries.plural');
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('store.inquiry.customer'))->schema([
                TextInput::make('customer_name')->label(__('store.inquiry.name'))->disabled()->dehydrated(false),
                TextInput::make('customer_phone')->label(__('store.inquiry.phone'))->disabled()->dehydrated(false),
                TextInput::make('customer_email')->label(__('store.checkout.email'))->disabled()->dehydrated(false),
                TextInput::make('product_label')
                    ->label(__('store.inquiry.product'))
                    ->disabled()
                    ->dehydrated(false)
                    ->formatStateUsing(fn (?ProductInquiry $record): string => (string) $record?->product?->name),
                Textarea::make('message')->label(__('store.inquiry.message'))->disabled()->dehydrated(false)->rows(3)->columnSpanFull(),
            ])->columns(2),
            Section::make(__('store.inquiry.reply'))->schema([
                Textarea::make('admin_reply')->label(__('store.inquiry.reply'))->rows(5)->required()->columnSpanFull(),
            ]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('customer_name')->label(__('store.inquiry.name'))->searchable(),
            TextColumn::make('customer_phone')->label(__('store.inquiry.phone'))->searchable(),
            TextColumn::make('product.name')->label(__('store.inquiry.product')),
            TextColumn::make('status')->label(__('admin.fields.status'))->badge(),
            TextColumn::make('created_at')->label(__('admin.fields.created'))->dateTime(),
        ])->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductInquiries::route('/'),
            'edit' => EditProductInquiry::route('/{record}/edit'),
        ];
    }
}
