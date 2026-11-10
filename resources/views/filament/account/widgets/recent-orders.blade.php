<div class="account-card p-5">
    <div class="flex items-center justify-between gap-3">
        <h2 class="text-lg font-bold text-[#131316]">{{ __('store.orders.plural') }}</h2>
        <a class="rounded-full bg-[#eeddb0] px-3 py-1 text-sm font-semibold text-[#131316] no-underline" href="{{ $indexUrl }}">{{ __('store.orders.plural') }}</a>
    </div>
    @forelse($orders as $order)
        <a class="mt-3 flex items-center justify-between gap-3 rounded-2xl border border-[#e6e6e6] bg-[#f6f6f8] px-4 py-3 text-sm text-[#131316] no-underline" href="{{ $orderUrl($order) }}">
            <span class="font-semibold">{{ $order->reference }}</span>
            <span>{{ __('store.track.steps.'.$order->status) }}</span>
            <span>{{ app(\App\Services\DecimalMath::class)->format((string) $order->total) }}</span>
        </a>
    @empty
        <p class="mt-3 text-sm text-[#212a2f]">{{ __('store.orders.empty') }}</p>
    @endforelse
</div>
