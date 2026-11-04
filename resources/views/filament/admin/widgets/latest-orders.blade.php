<section class="dash-orders">
    <header>
        <h2>{{ __('admin.stats.latest_orders') }}</h2>
    </header>
    @forelse($orders as $order)
        @php($statusLabel = __('admin.status.'.$order->status))
        @php($label = $statusLabel === 'admin.status.'.$order->status ? $order->status : $statusLabel)
        @php($share = app(\App\Services\DecimalMath::class)->comp($max, '0') === 0 ? 0 : max(8, ((float) $order->total / (float) $max) * 100))
        <a class="dash-order" href="{{ $orderUrl($order) }}">
            <div class="dash-order-top">
                <strong>{{ $order->customer_name }}</strong>
                <span>{{ $math->format((string) $order->total) }}</span>
            </div>
            <div class="dash-meter" aria-hidden="true">
                <span style="width: {{ $share }}%; background: {{ \App\Filament\Admin\Widgets\ChartPalette::STATUS[$order->status] ?? '#8C6A3D' }}"></span>
            </div>
            <div class="dash-order-meta">
                <span>{{ $order->reference }}</span>
                <span class="dash-status">{{ $label }}</span>
            </div>
        </a>
    @empty
        <p class="dash-empty">{{ __('admin.stats.empty') }}</p>
    @endforelse
</section>
<style>
    .dash-orders {
        display: grid;
        gap: 0.85rem;
        border: 1px solid rgb(226 232 240);
        border-radius: 1.15rem;
        background: #fff;
        padding: 1.1rem 1.15rem 1.2rem;
        box-shadow: 0 12px 30px rgb(15 23 42 / 6%);
    }
    .dark .dash-orders {
        border-color: rgb(255 255 255 / 10%);
        background: rgb(17 24 39);
        box-shadow: none;
        color: rgb(243 244 246);
    }
    .dash-orders h2 { margin: 0; font-size: 1.05rem; font-weight: 750; }
    .dash-order {
        display: grid;
        gap: 0.35rem;
        color: inherit;
        text-decoration: none;
    }
    .dash-order-top, .dash-order-meta {
        display: flex;
        justify-content: space-between;
        gap: 0.75rem;
        align-items: baseline;
    }
    .dash-order-top strong { font-size: 0.95rem; }
    .dash-order-top span { font-weight: 750; }
    .dash-order-meta { font-size: 0.78rem; opacity: 0.72; }
    .dash-status {
        border-radius: 999px;
        background: rgb(241 245 249);
        padding: 0.1rem 0.55rem;
        font-weight: 700;
        opacity: 1;
    }
    .dark .dash-status { background: rgb(31 41 55); }
    .dash-meter {
        height: 0.7rem;
        overflow: hidden;
        border-radius: 999px;
        background: rgb(226 232 240);
    }
    .dark .dash-meter { background: rgb(55 65 81); }
    .dash-meter span { display: block; height: 100%; border-radius: inherit; }
    .dash-empty { margin: 0; opacity: 0.7; }
</style>
