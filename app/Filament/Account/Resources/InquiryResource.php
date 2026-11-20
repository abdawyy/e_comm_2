<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Resources\Inquiries\Pages\ListInquiries;
use App\Filament\Account\Resources\Inquiries\Pages\ViewInquiry;
use App\Models\ProductInquiry;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class InquiryResource extends Resource
{
    protected static ?string $model = ProductInquiry::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedInbox;

    protected static ?int $navigationSort = 2;

    public static function getEloquentQuery(): Builder
    {
        return parent::getEloquentQuery()->where('user_id', auth()->id());
    }

    public static function canViewAny(): bool
    {
        return auth()->check();
    }

    public static function canView($record): bool
    {
        return auth()->id() === $record->user_id;
    }

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function canDelete($record): bool
    {
        return false;
    }

    public static function getModelLabel(): string
    {
        return __('store.account.request');
    }

    public static function getPluralModelLabel(): string
    {
        return __('store.account.requests');
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('store.account.request'))->schema([
                TextEntry::make('product.name')->label(__('store.inquiry.product')),
                TextEntry::make('customer_name')->label(__('store.inquiry.name')),
                TextEntry::make('customer_phone')->label(__('store.inquiry.phone')),
                TextEntry::make('status')->label(__('store.orders.status')),
                TextEntry::make('message')->label(__('store.inquiry.message'))->placeholder('—')->columnSpanFull(),
                TextEntry::make('admin_reply')->label(__('store.inquiry.reply'))->placeholder(__('store.account.reply_waiting'))->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('product.name')->label(__('store.inquiry.product')),
            TextColumn::make('status')->label(__('store.orders.status'))->badge(),
            TextColumn::make('created_at')->label(__('store.orders.placed'))->dateTime(),
        ])->recordActions([
            ViewAction::make(),
        ])->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListInquiries::route('/'),
            'view' => ViewInquiry::route('/{record}'),
        ];
    }
}
