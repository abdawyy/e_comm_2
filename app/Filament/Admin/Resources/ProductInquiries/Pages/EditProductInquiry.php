<?php

namespace App\Filament\Admin\Resources\ProductInquiries\Pages;

use App\Filament\Admin\Resources\ProductInquiryResource;
use App\Mail\InquiryReplyMail;
use Filament\Notifications\Notification;
use Filament\Resources\Pages\EditRecord;
use Illuminate\Support\Facades\Mail;

class EditProductInquiry extends EditRecord
{
    protected static string $resource = ProductInquiryResource::class;

    protected function afterSave(): void
    {
        $inquiry = $this->getRecord();

        if (blank($inquiry->admin_reply)) {
            return;
        }

        $inquiry->forceFill([
            'status' => 'replied',
            'replied_at' => now(),
            'replied_by' => auth()->id(),
        ])->save();

        if (filled($inquiry->customer_email)) {
            Mail::to($inquiry->customer_email)->send(new InquiryReplyMail($inquiry));
            Notification::make()->title(__('store.inquiry.reply_sent'))->success()->send();

            return;
        }

        Notification::make()
            ->title(__('store.inquiry.reply_saved'))
            ->body(__('store.inquiry.call_customer', ['phone' => $inquiry->customer_phone, 'name' => $inquiry->customer_name]))
            ->success()
            ->send();
    }
}
