<?php

namespace App\Http\Controllers;

use App\Models\Order;
use App\Support\Phone;
use Illuminate\Http\Request;
use Illuminate\View\View;

class TrackOrderController extends Controller
{
    public function __invoke(Request $request): View
    {
        $order = null;
        $miss = false;

        if ($request->filled('reference')) {
            $order = $this->locate($request);
            $miss = $order === null;
        }

        return view('store.track', [
            'order' => $order,
            'miss' => $miss,
        ]);
    }

    private function locate(Request $request): ?Order
    {
        $reference = strtoupper(trim((string) $request->query('reference')));
        $order = Order::query()->with('items')->where('reference', $reference)->first();

        if (! $order) {
            return null;
        }

        $user = $request->user();

        if ($user && $user->id === $order->user_id) {
            return $order;
        }

        if (session('checkout.reference') === $order->reference) {
            return $order;
        }

        if (Phone::same(Phone::normalize((string) $request->query('phone')), (string) $order->customer_phone)) {
            return $order;
        }

        return null;
    }
}
