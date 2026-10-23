<?php

namespace App\Filament\Account\Resources;

use App\Filament\Account\Resources\Orders\Pages\ListOrders;
use App\Filament\Account\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use Filament\Actions\ViewAction;
use Filament\Infolists\Components\ViewEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Builder;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

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
        return __('store.orders.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('store.orders.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->components([
            ViewEntry::make('reference')
                ->hiddenLabel()
                ->view('filament.account.order-tracking')
                ->viewData(fn (Order $record) => ['order' => $record->loadMissing('items')]),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('reference')->label(__('store.orders.reference')),
            TextColumn::make('status')->label(__('store.orders.status')),
            TextColumn::make('payment_status')->label(__('store.orders.payment')),
            TextColumn::make('total')->label(__('store.orders.total')),
            TextColumn::make('created_at')->dateTime()->label(__('store.orders.placed')),
        ])->recordActions([
            ViewAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
        ];
    }
}
