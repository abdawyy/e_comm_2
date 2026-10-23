<article class="traffic-card">
    <h2 class="text-base font-semibold">{{ $title }}</h2>
    <p class="text-sm opacity-70">{{ $help }}</p>
    @php($top = max(1, collect($rows)->max() ?: 1))
    @forelse($rows as $name => $count)
        <div class="traffic-row">
            <p><span>{{ $name }}</span><strong>{{ $count }}</strong></p>
            <div class="traffic-meter"><span style="width: {{ (int) round($count / $top * 100) }}%"></span></div>
        </div>
    @empty
        <p class="mt-3 text-sm opacity-70">{{ __('admin.traffic.empty') }}</p>
    @endforelse
</article>
