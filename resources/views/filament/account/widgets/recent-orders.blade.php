<div class="rounded-xl border p-4">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-lg font-semibold">{{ __('store.orders.plural') }}</h2>
        <a class="text-sm underline" href="{{ $indexUrl }}">{{ __('store.orders.plural') }}</a>
    </div>
    @forelse($orders as $order)
        <p class="mt-3 text-sm">
            <a class="underline" href="{{ $orderUrl($order) }}">{{ $order->reference }}</a>
            — {{ __('store.track.steps.'.$order->status) }}
            — {{ app(\App\Services\DecimalMath::class)->format((string) $order->total) }}
        </p>
    @empty
        <p class="mt-3 text-sm">{{ __('store.orders.empty') }}</p>
    @endforelse
</div>
