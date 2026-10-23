<?php

namespace App\Actions;

use App\Models\Order;
use App\Models\Product;
use App\Services\DecimalMath;
use Illuminate\Support\Facades\DB;

class RestoreStock
{
    public function __construct(private DecimalMath $math) {}

    public function execute(Order $order): void
    {
        DB::transaction(function () use ($order) {
            $order->load('items');

            foreach ($order->items as $item) {
                if ($item->kind !== 'product' || ! $item->product_id) {
                    continue;
                }

                $product = Product::query()->lockForUpdate()->find($item->product_id);

                if ($product && $product->track_stock) {
                    $product->stock_quantity = $this->math->add((string) $product->stock_quantity, (string) $item->quantity);
                    $product->save();
                }
            }
        });
    }
}
