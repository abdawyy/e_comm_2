<?php

namespace App\Filament\Admin\Pages;

use App\Models\Order;
use App\Models\ShippingCarrier;
use App\Services\DecimalMath;
use Filament\Pages\Page;
use Filament\Schemas\Components\View;
use Filament\Schemas\Schema;
use Filament\Support\Icons\Heroicon;

class DeliveryAnalytics extends Page
{
    protected static string|\BackedEnum|null $navigationIcon = Heroicon::OutlinedTruck;

    protected static ?string $slug = 'delivery';

    protected static ?int $navigationSort = 5;

    public static function getNavigationGroup(): ?string
    {
        return __('admin.groups.delivery');
    }

    public static function getNavigationLabel(): string
    {
        return __('admin.pages.delivery');
    }

    public function getTitle(): string
    {
        return __('admin.pages.delivery');
    }

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('viewAny', Order::class);
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            View::make('filament.admin.delivery')->viewData(function () {
                $math = app(DecimalMath::class);
                $carriers = ShippingCarrier::query()->orderBy('sort_order')->get();
                $assigned = Order::query()->whereNotNull('shipping_carrier_id')->get(['id', 'shipping_carrier_id', 'shipping_label_fee', 'reference', 'customer_name', 'status']);
                $rows = [];

                foreach ($carriers as $carrier) {
                    $orders = $assigned->where('shipping_carrier_id', $carrier->id);
                    $fee = '0';
                    $feeOrders = 0;

                    foreach ($orders as $order) {
                        if ($order->shipping_label_fee === null) {
                            continue;
                        }

                        $fee = $math->add($fee, (string) $order->shipping_label_fee);
                        $feeOrders++;
                    }

                    $rows[] = [
                        'name' => (string) $carrier->name,
                        'orders' => $orders->count(),
                        'fee_orders' => $feeOrders,
                        'fees' => $math->format($fee),
                    ];
                }

                return [
                    'rows' => $rows,
                    'unassigned' => Order::query()->whereNull('shipping_carrier_id')->count(),
                    'recent' => Order::query()->with('shippingCarrier')->whereNotNull('shipping_carrier_id')->latest('id')->limit(12)->get(),
                    'math' => $math,
                ];
            }),
        ]);
    }
}
