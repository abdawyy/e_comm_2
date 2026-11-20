<?php

namespace App\Http\Controllers;

use App\Mail\InquiryReceivedMail;
use App\Models\Product;
use App\Models\ProductInquiry;
use App\Models\StoreSetting;
use App\Models\User;
use Filament\Notifications\Notification;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Mail;

class ProductInquiryController extends Controller
{
    public function store(Request $request, Product $product): RedirectResponse
    {
        abort_unless($product->is_active && $product->acceptsInquiry(), 404);

        $data = $request->validate([
            'customer_name' => ['required', 'string', 'max:120'],
            'customer_phone' => ['required', 'string', 'max:32'],
            'customer_email' => ['nullable', 'email', 'max:190'],
            'message' => ['nullable', 'string', 'max:1000'],
        ]);

        $inquiry = ProductInquiry::query()->create([
            'product_id' => $product->id,
            'user_id' => $request->user()?->id,
            'customer_name' => $data['customer_name'],
            'customer_phone' => $data['customer_phone'],
            'customer_email' => $data['customer_email'] ?? $request->user()?->email,
            'message' => $data['message'] ?? null,
            'status' => 'new',
        ]);
        $inquiry->load('product');

        $admins = User::query()->where('is_admin', true)->get();
        $inbox = StoreSetting::orderEmail() ?: config('store.admin_email');

        if (filled($inbox)) {
            Mail::to($inbox)->send(new InquiryReceivedMail($inquiry));
        }

        if (filled($inquiry->customer_email)) {
            Mail::to($inquiry->customer_email)->send(new InquiryReceivedMail($inquiry));
        }

        if ($admins->isNotEmpty()) {
            Notification::make()
                ->title(__('store.inquiry.notification', ['name' => $inquiry->customer_name]))
                ->body($inquiry->customer_phone.' — '.$product->name)
                ->sendToDatabase($admins);
        }

        return back()->with('status', __('store.inquiry.sent'));
    }
}
