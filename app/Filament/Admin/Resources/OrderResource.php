<?php

namespace App\Filament\Admin\Resources;

use App\Filament\Admin\Resources\Orders\Pages\EditOrder;
use App\Filament\Admin\Resources\Orders\Pages\ListOrders;
use App\Filament\Admin\Resources\Orders\Pages\ViewOrder;
use App\Models\Order;
use App\Services\DecimalMath;
use Filament\Actions\ViewAction;
use Filament\Forms\Components\Select;
use Filament\Forms\Components\Textarea;
use Filament\Infolists\Components\TextEntry;
use Filament\Resources\Resource;
use Filament\Schemas\Components\Section;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;

class OrderResource extends Resource
{
    protected static ?string $model = Order::class;

    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedClipboardDocumentList;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.sales');
    }

    public static function getModelLabel(): string
    {
        return __('admin.resources.orders.label');
    }

    public static function getPluralModelLabel(): string
    {
        return __('admin.resources.orders.plural');
    }

    public static function form(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('admin.forms.publish'))->icon(Heroicon::OutlinedClipboardDocumentList)->schema([
                Select::make('status')->label(__('admin.fields.status'))->options(self::statusOptions())->required()->native(false),
                Select::make('payment_status')->label(__('admin.fields.payment_status'))->options([
                    'unpaid' => __('admin.payment.unpaid'),
                    'pending' => __('admin.payment.pending'),
                    'paid' => __('admin.payment.paid'),
                    'failed' => __('admin.payment.failed'),
                    'refunded' => __('admin.payment.refunded'),
                ])->native(false)->disabled(fn () => ! auth()->user()?->can('update', Order::class)),
                Textarea::make('notes')->label(__('admin.fields.notes'))->rows(4)->columnSpanFull(),
            ])->columns(2),
        ]);
    }

    public static function infolist(Schema $schema): Schema
    {
        return $schema->columns(1)->components([
            Section::make(__('admin.forms.details'))->icon(Heroicon::OutlinedClipboardDocumentList)->schema([
                TextEntry::make('reference')->label(__('admin.fields.reference')),
                TextEntry::make('status')->label(__('admin.fields.status')),
                TextEntry::make('payment_status')->label(__('admin.fields.payment_status')),
                TextEntry::make('payment_gateway')->label(__('admin.fields.gateway')),
                TextEntry::make('customer_name')->label(__('admin.fields.customer')),
                TextEntry::make('customer_phone')->label(__('admin.fields.phone')),
                TextEntry::make('city_name')->label(__('admin.fields.city')),
                TextEntry::make('address')->label(__('admin.fields.address')),
                TextEntry::make('total')->label(__('admin.fields.total'))->formatStateUsing(fn ($state) => app(DecimalMath::class)->format((string) $state)),
                TextEntry::make('masked_pan')->label(__('admin.fields.masked_pan')),
            ])->columns(2),
        ]);
    }

    public static function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('reference')->searchable()->label(__('admin.fields.reference')),
            TextColumn::make('customer_name')->searchable()->label(__('admin.fields.customer')),
            TextColumn::make('status')->badge()->label(__('admin.fields.status')),
            TextColumn::make('payment_status')->badge()->label(__('admin.fields.payment_status')),
            TextColumn::make('total')->label(__('admin.fields.total')),
            TextColumn::make('created_at')->dateTime()->label(__('admin.fields.created')),
        ])->recordActions([
            ViewAction::make(),
            \Filament\Actions\EditAction::make(),
        ])->defaultSort('id', 'desc');
    }

    public static function getPages(): array
    {
        return [
            'index' => ListOrders::route('/'),
            'view' => ViewOrder::route('/{record}'),
            'edit' => EditOrder::route('/{record}/edit'),
        ];
    }

    /**
     * @return array<string, string>
     */
    public static function statusOptions(): array
    {
        return [
            'pending' => __('admin.status.pending'),
            'confirmed' => __('admin.status.confirmed'),
            'processing' => __('admin.status.processing'),
            'shipped' => __('admin.status.shipped'),
            'delivered' => __('admin.status.delivered'),
            'cancelled' => __('admin.status.cancelled'),
        ];
    }
}
