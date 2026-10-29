<?php

namespace App\Filament\Account\Resources\Orders\Pages;

use App\Filament\Account\Resources\OrderResource;
use App\Models\Order;
use App\Services\DecimalMath;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('invoice')
                ->label(__('store.orders.invoice'))
                ->url(fn (Order $record) => route('invoices.download', $record))
                ->openUrlInNewTab(),
            Action::make('cancel')
                ->label(__('store.orders.cancel'))
                ->color('danger')
                ->requiresConfirmation()
                ->visible(fn (Order $record) => in_array($record->status, ['pending', 'confirmed'], true))
                ->action(function (Order $record) {
                    if ($record->payment_status === 'paid') {
                        $record->orderNotes()->create([
                            'user_id' => auth()->id(),
                            'type' => 'staff_task',
                            'body' => 'Customer requested cancellation of a paid Visa order. Staff review required. No automatic refund was issued.',
                        ]);
                        Notification::make()->title(__('store.orders.cancel_review'))->warning()->send();

                        return;
                    }

                    if (! $record->canCustomerCancel()) {
                        return;
                    }

                    $record->status = 'cancelled';
                    $record->save();
                    Notification::make()->title(__('store.orders.cancelled'))->success()->send();
                }),
        ];
    }
}
