<div class="space-y-4">
    <p class="text-sm">{{ $order->reference }} · {{ $order->city_name }}</p>
    @include('store.partials.order-timeline', ['order' => $order])
    <ul class="space-y-2 text-sm">
        @foreach($order->items as $item)
            <li>{{ $item->localizedName() }} × {{ $item->quantity }} — {{ app(\App\Services\DecimalMath::class)->format((string) $item->line_subtotal) }}</li>
        @endforeach
    </ul>
    <p>{{ __('store.checkout.total') }} {{ app(\App\Services\DecimalMath::class)->format((string) $order->total) }}</p>
    <p class="text-sm">{{ $order->address }}</p>
    <p><a href="{{ route('invoices.download', $order) }}">{{ __('store.orders.invoice') }}</a></p>
</div>
