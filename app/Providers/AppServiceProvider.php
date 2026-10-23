<?php

namespace App\Providers;

use App\Contracts\PaymentGatewayInterface;
use App\Contracts\SmsSender;
use App\Events\OrderCreated;
use App\Events\OrderPaid;
use App\Events\OrderStatusChanged;
use App\Listeners\HandleOrderCreated;
use App\Listeners\HandleOrderPaid;
use App\Listeners\HandleOrderStatusChanged;
use App\Services\CustomerInsights;
use App\Services\Payments\CashOnDeliveryGateway;
use App\Services\Payments\PaymobCardGateway;
use App\Services\Sms\HttpSmsSender;
use App\Services\Sms\LogSmsSender;
use Illuminate\Support\Facades\Event;
use Illuminate\Support\ServiceProvider;

class AppServiceProvider extends ServiceProvider
{
    public function register(): void
    {
        config([
            'filament-shield.super_admin.define_via_gate' => true,
            'filament-shield.panel_user.enabled' => false,
        ]);

        $this->app->bind(CashOnDeliveryGateway::class);
        $this->app->bind(PaymobCardGateway::class);
        $this->app->tag([CashOnDeliveryGateway::class, PaymobCardGateway::class], 'payment.gateways');
        $this->app->bind(PaymentGatewayInterface::class, CashOnDeliveryGateway::class);

        $this->app->bind(SmsSender::class, function () {
            return config('store.sms_driver') === 'http'
                ? $this->app->make(HttpSmsSender::class)
                : $this->app->make(LogSmsSender::class);
        });

        $this->app->scoped(CustomerInsights::class);
    }

    public function boot(): void
    {
        Event::listen(OrderCreated::class, HandleOrderCreated::class);
        Event::listen(OrderPaid::class, HandleOrderPaid::class);
        Event::listen(OrderStatusChanged::class, HandleOrderStatusChanged::class);
    }
}
