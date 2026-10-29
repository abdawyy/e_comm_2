<?php

namespace App\Http\Controllers;

use App\Actions\GenerateInvoiceAction;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Symfony\Component\HttpFoundation\StreamedResponse;

class InvoiceController extends Controller
{
    public function __invoke(Request $request, Order $order, GenerateInvoiceAction $invoices): StreamedResponse
    {
        if (! $this->allowed($request, $order)) {
            abort(403);
        }

        if (! $order->invoice_path || ! Storage::disk('local')->exists($order->invoice_path)) {
            $invoices->execute($order);
            $order->refresh();
        }

        return Storage::disk('local')->download($order->invoice_path, $order->reference.'.pdf');
    }

    private function allowed(Request $request, Order $order): bool
    {
        if ($request->hasValidSignature()) {
            return true;
        }

        $user = $request->user();

        if ($user && $user->id === $order->user_id) {
            return true;
        }

        if ($user && $user->is_admin && $user->can('view', $order)) {
            return true;
        }

        return false;
    }
}
