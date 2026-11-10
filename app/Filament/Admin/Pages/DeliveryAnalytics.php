<?php

namespace App\Filament\Admin\Pages;

use App\Models\Order;
use App\Models\ShippingCarrier;
use App\Services\DecimalMath;
use Filament\Actions\Action;
use Filament\Forms\Components\Select;
use Filament\Notifications\Notification;
use Filament\Pages\Page;
use Filament\Schemas\Components\Actions;
use Filament\Schemas\Components\EmbeddedSchema;
use Filament\Schemas\Components\Form;
use Filament\Schemas\Components\Section;
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

    public ?array $data = [];

    public static function canAccess(): bool
    {
        return (bool) auth()->user()?->can('viewAny', Order::class);
    }

    public function mount(): void
    {
        $this->form->fill([
            'order_id' => null,
            'shipping_carrier_id' => null,
        ]);
    }

    public function defaultForm(Schema $schema): Schema
    {
        return $schema->statePath('data');
    }

    public function form(Schema $schema): Schema
    {
        return $schema->components([
            Section::make(__('store.delivery.assign'))->schema([
                Select::make('order_id')->label(__('store.delivery.assign_order'))->options(function (): array {
                    return Order::query()->with('shippingCarrier')->latest('id')->limit(200)->get()->mapWithKeys(function (Order $order): array {
                        $carrier = $order->shippingCarrier?->name;

                        return [$order->id => $order->reference.' — '.$order->customer_name.($carrier ? ' — '.$carrier : '')];
                    })->all();
                })->searchable()->required()->native(false),
                Select::make('shipping_carrier_id')->label(__('store.delivery.carrier'))->options(fn (): array => ShippingCarrier::query()->where('is_active', true)->orderBy('sort_order')->get()->mapWithKeys(fn (ShippingCarrier $carrier): array => [$carrier->id => (string) $carrier->name])->all())->searchable()->required()->native(false),
            ])->columns(2),
        ]);
    }

    public function assign(): void
    {
        $state = $this->form->getState();
        $order = Order::query()->findOrFail($state['order_id']);
        abort_unless(auth()->user()?->can('update', $order), 403);
        $order->shipping_carrier_id = $state['shipping_carrier_id'];
        $order->save();
        $this->form->fill([
            'order_id' => null,
            'shipping_carrier_id' => null,
        ]);
        Notification::make()->title(__('store.delivery.assign_saved'))->success()->send();
    }

    public function content(Schema $schema): Schema
    {
        return $schema->components([
            Form::make([EmbeddedSchema::make('form')])
                ->id('assign-carrier')
                ->livewireSubmitHandler('assign')
                ->footer([
                    Actions::make([
                        Action::make('assign')->label(__('store.delivery.assign'))->submit('assign'),
                    ]),
                ]),
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
                    'waiting' => Order::query()->whereNull('shipping_carrier_id')->latest('id')->limit(20)->get(['id', 'reference', 'customer_name', 'city_name']),
                    'groups' => $carriers->map(function (ShippingCarrier $carrier) use ($assigned) {
                        return [
                            'name' => (string) $carrier->name,
                            'orders' => $assigned->where('shipping_carrier_id', $carrier->id)->sortByDesc('id')->values(),
                        ];
                    })->filter(fn (array $group): bool => $group['orders']->isNotEmpty())->values(),
                    'math' => $math,
                ];
            }),
        ]);
    }
}
