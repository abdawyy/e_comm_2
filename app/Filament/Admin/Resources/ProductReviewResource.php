<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\ProductReviews\Pages\EditProductReview;
use App\Filament\Admin\Resources\ProductReviews\Pages\ListProductReviews;
use App\Models\ProductReview;
use Filament\Actions\EditAction;
use Filament\Forms\Components\Textarea;
use Filament\Forms\Components\TextInput;
use Filament\Forms\Components\Toggle;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\IconColumn;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class ProductReviewResource extends Resource
{
    protected static ?string $model = ProductReview::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedStar;

    protected static ?int $navigationSort = 12;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.catalog');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.reviews.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.reviews.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->components([
            TextInput::make('author_name')->label(__('store.product.your_name'))->required()->maxLength(80),
            TextInput::make('rating')->label(__('store.product.stars'))->numeric()->minValue(1)->maxValue(5)->required(),
            Textarea::make('body')->label(__('store.product.your_review'))->required()->rows(4)->columnSpanFull(),
            Toggle::make('is_approved')->label(__('admin.fields.active')),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('product.name')->label(__('admin.resources.products.label')),
            TextColumn::make('author_name')->label(__('store.product.your_name'))->searchable(),
            TextColumn::make('rating')->label(__('store.product.stars')),
            TextColumn::make('body')->label(__('store.product.your_review'))->limit(60)->searchable(),
            IconColumn::make('is_approved')->label(__('admin.fields.active'))->boolean(),
        ])->recordActions([
            EditAction::make(),
        ])->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListProductReviews::route('/'),
            'edit' => EditProductReview::route('/{record}/edit'),
        ];
    }
}
