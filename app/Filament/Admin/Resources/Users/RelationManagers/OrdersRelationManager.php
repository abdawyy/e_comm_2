<?php

namespace App\Filament\Admin\Resources\Users\RelationManagers;

use App\Filament\Admin\Resources\OrderResource;
use App\Models\Order;
use App\Services\DecimalMath;
use Filament\Actions\Action;
use Filament\Resources\RelationManagers\RelationManager;
use Filament\Tables\Columns\TextColumn;
use Filament\Tables\Table;
use Illuminate\Database\Eloquent\Model;

class OrdersRelationManager extends RelationManager
{
    protected static string $relationship = 'orders';

    public static function getTitle(Model $ownerRecord, string $pageClass): string
    {
        return __('admin.insights.orders');
    }

    public function table(Table $table): Table
    {
        return $table->columns([
            TextColumn::make('reference')->label(__('admin.fields.reference'))->searchable(),
            TextColumn::make('status')->label(__('admin.fields.status'))->formatStateUsing(fn (string $state) => __('admin.status.'.$state)),
            TextColumn::make('total')->label(__('admin.fields.total'))->formatStateUsing(fn ($state) => app(DecimalMath::class)->format((string) $state)),
            TextColumn::make('city_name')->label(__('admin.fields.city')),
            TextColumn::make('address')->label(__('admin.fields.address'))->wrap(),
            TextColumn::make('created_at')->dateTime()->label(__('admin.insights.last_order')),
        ])->recordActions([
            Action::make('open')->label(__('admin.actions.open'))->url(fn (Order $record) => OrderResource::getUrl('view', ['record' => $record])),
        ])->defaultSort('created_at', 'desc');
    }
}
