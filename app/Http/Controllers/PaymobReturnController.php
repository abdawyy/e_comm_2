<?php

namespace App\Http\Controllers;

use App\Models\Order;
use Illuminate\Http\Request;
use Illuminate\View\View;

class PaymobReturnController extends Controller
{
    public function __invoke(Request $request): View
    {
        $reference = (string) $request->query('reference', session('checkout.reference'));
        $order = $reference !== '' ? Order::query()->where('reference', $reference)->first() : null;

        return view('store.paymob-return', [
            'order' => $order,
        ]);
    }

    public function status(Request $request): \Illuminate\Http\JsonResponse
    {
        $reference = (string) $request->query('reference');
        $sessionReference = (string) session('checkout.reference');

        if ($reference === '' || ! hash_equals($sessionReference, $reference)) {
            abort(404);
        }

        $order = Order::query()->where('reference', $reference)->first();

        return response()->json([
            'payment_status' => $order?->payment_status,
            'status' => $order?->status,
        ]);
    }
}
