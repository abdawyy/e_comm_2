<?php

namespace App\Filament\Admin\Resources\Orders\Pages;

use App\Actions\GenerateInvoiceAction;
use App\Actions\SendOrderNotifications;
use App\Filament\Admin\Resources\OrderResource;
use App\Models\Order;
use Filament\Actions\Action;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\ViewRecord;
use Throwable;

class ViewOrder extends ViewRecord
{
    protected static string $resource = OrderResource::class;

    protected function getHeaderActions(): array
    {
        return [
            Action::make('sendCustomer')
                ->label(__('admin.mail.send_customer'))
                ->visible(fn (Order $record): bool => filled($record->customer_email))
                ->action(fn (Order $record) => $this->sendInvoice($record, (string) $record->customer_email)),
            Action::make('sendAdmin')
                ->label(__('admin.mail.send_admin'))
                ->action(function (Order $record): void {
                    $email = auth()->user()?->email ?: (string) config('store.admin_email');
                    $this->sendInvoice($record, $email);
                }),
        ];
    }

    private function sendInvoice(Order $order, string $email): void
    {
        if (blank($email)) {
            Notification::make()->title(__('admin.mail.no_address'))->danger()->send();

            return;
        }

        try {
            app(GenerateInvoiceAction::class)->execute($order);
            app(SendOrderNotifications::class)->sendInvoiceTo($order->fresh(), $email);
            Notification::make()->title(__('admin.mail.sent'))->success()->send();
        } catch (Throwable $exception) {
            $order->forceFill([
                'mail_status' => 'failed',
                'mail_error' => $exception->getMessage(),
            ])->save();
            Notification::make()->title(__('admin.mail.failed'))->body($exception->getMessage())->danger()->send();
        }
    }
}
