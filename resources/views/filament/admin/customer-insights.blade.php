<div class="col-span-full rounded-xl border p-4">
    <h3 class="font-semibold">{{ __('admin.insights.activity') }}</h3>
    @foreach($insights['activity'] as $row)
        <p class="text-sm">{{ $row['label'] }} — {{ __('admin.insights.views_short') }} {{ $row['views'] }} / {{ __('admin.insights.orders_short') }} {{ $row['orders'] }}</p>
    @endforeach
    <p class="mt-2 text-sm">{{ __('admin.insights.first_seen') }}: {{ optional($insights['first_seen'])->toDateTimeString() }}</p>
    <p class="text-sm">{{ __('admin.insights.last_seen') }}: {{ optional($insights['last_seen'])->toDateTimeString() }}</p>
</div>
