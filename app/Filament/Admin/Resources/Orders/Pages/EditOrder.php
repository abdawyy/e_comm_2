<?php

namespace App\Filament\Admin\Resources\Orders\Pages;

use App\Filament\Admin\Resources\OrderResource;
use Filament\Resources\Pages\EditRecord;

class EditOrder extends EditRecord
{
    protected static string $resource = OrderResource::class;

    /**
     * @param  array<string, mixed>  $data
     */
    protected function handleRecordUpdate(\Illuminate\Database\Eloquent\Model $record, array $data): \Illuminate\Database\Eloquent\Model
    {
        $payment = $data['payment_status'] ?? null;
        unset($data['payment_status']);
        $record->update($data);

        if (is_string($payment) && auth()->user()?->can('update', $record)) {
            $record->forceFill(['payment_status' => $payment])->save();
        }

        return $record;
    }
}
