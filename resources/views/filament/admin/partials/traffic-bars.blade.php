<div @class(['traffic-chart', 'is-short' => empty($tall)])>
    @forelse($rows as $row)
        <div class="traffic-col" title="{{ $row['label'] }}: {{ $row['pages'] }} / {{ $row['products'] }} / {{ $row['orders'] }}">
            <div class="traffic-stack">
                @if($row['pages'] > 0)<span class="is-pages" style="height: {{ max($row['pages_pct'], 4) }}%"></span>@endif
                @if($row['products'] > 0)<span class="is-products" style="height: {{ max($row['products_pct'], 4) }}%"></span>@endif
                @if($row['orders'] > 0)<span class="is-orders" style="height: {{ max($row['orders_pct'], 4) }}%"></span>@endif
            </div>
            <small>{{ $loop->count > 16 && $loop->iteration % (int) ceil($loop->count / 8) !== 1 ? '' : $row['label'] }}</small>
        </div>
    @empty
        <p class="text-sm opacity-70">{{ __('admin.traffic.empty') }}</p>
    @endforelse
</div>
