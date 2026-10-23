<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\PaymentTransactions\Pages\ListPaymentTransactions;
use App\Filament\Admin\Resources\PaymentTransactions\Pages\ViewPaymentTransaction;
use App\Models\PaymentTransaction;
use Filament\Actions\ViewAction;
use Filament\Resources\Resource;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class PaymentTransactionResource extends Resource
{
    protected static ?string $model = PaymentTransaction::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedCreditCard;

    public static function canCreate(): bool
    {
        return false;
    }

    public static function canEdit($record): bool
    {
        return false;
    }

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.sales');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.payments.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.payments.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema;
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('order.reference')->label(__('admin.fields.reference')),
            TextColumn::make('gateway')->label(__('admin.fields.gateway')),
            TextColumn::make('status')->badge()->label(__('admin.fields.status')),
            TextColumn::make('amount_cents')->label(__('admin.fields.amount_cents')),
            TextColumn::make('masked_pan')->label(__('admin.fields.masked_pan')),
            TextColumn::make('created_at')->dateTime()->label(__('admin.fields.created')),
        ])->recordActions([
            ViewAction::make(),
        ]);
    }

    public static function getPages(): array
    {
        return [
            'index' => ListPaymentTransactions::route('/'),
            'view' => ViewPaymentTransaction::route('/{record}'),
        ];
    }
}
