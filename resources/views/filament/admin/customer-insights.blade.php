<div class="col-span-full rounded-xl border p-4">
    <h3 class="font-semibold">{{ __('admin.insights.activity') }}</h3>
    @foreach($insights['activity'] as $row)
        <p class="text-sm">{{ $row['label'] }} — {{ __('admin.insights.views_short') }} {{ $row['views'] }} / {{ __('admin.insights.orders_short') }} {{ $row['orders'] }}</p>
    @endforeach
    <p class="mt-2 text-sm">{{ __('admin.insights.first_seen') }}: {{ $insights['first_seen'] ? $insights['first_seen']->timezone(config('store.analytics_timezone', 'Africa/Cairo'))->format('Y-m-d H:i') : '—' }}</p>
    <p class="text-sm">{{ __('admin.insights.last_seen') }}: {{ $insights['last_seen'] ? $insights['last_seen']->timezone(config('store.analytics_timezone', 'Africa/Cairo'))->format('Y-m-d H:i') : '—' }}</p>
    <p class="text-sm">{{ __('admin.insights.coupons') }}: {{ $insights['coupons'] !== [] ? implode(', ', $insights['coupons']) : '—' }}</p>
    <h3 class="mt-3 font-semibold">{{ __('admin.insights.visits') }}</h3>
    @forelse($insights['visits'] as $visit)
        <p class="text-sm">{{ $visit['at'] ? $visit['at']->timezone(config('store.analytics_timezone', 'Africa/Cairo'))->format('Y-m-d H:i') : '—' }} — {{ $visit['path'] }}</p>
    @empty
        <p class="text-sm">—</p>
    @endforelse
    <h3 class="mt-3 font-semibold">{{ __('admin.insights.purchases') }}</h3>
    @forelse($insights['recent'] as $order)
        <p class="text-sm">{{ $order->created_at ? $order->created_at->timezone(config('store.analytics_timezone', 'Africa/Cairo'))->format('Y-m-d H:i') : '—' }} — {{ $order->reference }} — {{ $order->coupon_code ?: '—' }}</p>
    @empty
        <p class="text-sm">—</p>
    @endforelse
</div>
