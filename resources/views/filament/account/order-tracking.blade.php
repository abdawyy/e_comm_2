<div class="account-card space-y-4 p-5 text-[#131316]">
    <p class="text-sm font-semibold">{{ $order->reference }} · {{ $order->city_name }}</p>
    @include('store.partials.order-timeline', ['order' => $order])
    <ul class="space-y-2 text-sm">
        @foreach($order->items as $item)
            <li class="rounded-xl bg-[#f6f6f8] px-3 py-2">{{ $item->localizedName() }} × {{ $item->quantity }} — {{ app(\App\Services\DecimalMath::class)->format((string) $item->line_subtotal) }}</li>
        @endforeach
    </ul>
    <p class="font-bold">{{ __('store.checkout.total') }} {{ app(\App\Services\DecimalMath::class)->format((string) $order->total) }}</p>
    <p class="text-sm">{{ $order->address }}</p>
    <p><a class="inline-flex rounded-full bg-[#131316] px-4 py-2 text-sm font-semibold text-white no-underline" href="{{ route('invoices.download', $order) }}">{{ __('store.orders.invoice') }}</a></p>
</div>
