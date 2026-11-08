<?php

namespace App\Http\Controllers;

use App\Actions\GenerateInvoiceAction;
use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\Http\Response;

class InvoiceController extends Controller
{
    public function __invoke(Request $request, Order $order, GenerateInvoiceAction $invoices): Response
    {
        if (! $this->allowed($request, $order)) {
            abort(403);
        }

        $filename = preg_replace('/[^A-Za-z0-9._-]/', '', (string) $order->reference) ?: 'invoice';

        return response($invoices->execute($order), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'.pdf"',
        ]);
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
