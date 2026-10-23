<section class="insight-card">
    <div class="insight-head">
        <div>
            <h2>{{ __('admin.traffic.filters') }}</h2>
            <p>{{ $report['range_label'] }}</p>
        </div>
        <button type="button" class="insight-reset" wire:click="resetFilters">{{ __('admin.traffic.reset') }}</button>
    </div>
    <div class="insight-chips">
        @foreach(['today', '7', '30', '90', '365', 'custom'] as $option)
            <button type="button" wire:click="setPeriod('{{ $option }}')" @class(['insight-chip', 'is-on' => $period === $option])>{{ __('admin.periods.'.$option) }}</button>
        @endforeach
    </div>
    <div class="insight-filters">
        <label>{{ __('admin.traffic.from') }}
            <input type="date" wire:model.live="from">
        </label>
        <label>{{ __('admin.traffic.until') }}
            <input type="date" wire:model.live="until">
        </label>
        <label>{{ __('admin.traffic.device') }}
            <select wire:model.live="device">
                <option value="all">{{ __('admin.traffic.all_devices') }}</option>
                <option value="desktop">{{ __('admin.traffic.desktop') }}</option>
                <option value="mobile">{{ __('admin.traffic.mobile_device') }}</option>
                <option value="tablet">{{ __('admin.traffic.tablet') }}</option>
            </select>
        </label>
        <label>{{ __('admin.traffic.place') }}
            <select wire:model.live="place">
                <option value="all">{{ __('admin.traffic.all_places') }}</option>
                @foreach($placeOptions as $option)
                    <option value="{{ $option }}">{{ $option }}</option>
                @endforeach
            </select>
        </label>
    </div>
</section>

<section class="insight-kpis">
    @foreach([
        ['active', $report['active'], 'active_help', '#16a34a'],
        ['sessions', $report['sessions'], 'sessions_help', '#0284c7'],
        ['views', $report['page_views'], 'views_help', '#7c3aed'],
        ['orders', $report['orders'], 'orders_help', '#f97316'],
        ['conversion', $report['conversion'].'%', 'conversion_help', '#0f766e'],
    ] as [$key, $value, $help, $color])
        <article class="insight-kpi" style="--kpi: {{ $color }}">
            <p>{{ __('admin.traffic.'.$key) }}</p>
            <strong>{{ $value }}</strong>
            <small>{{ __('admin.traffic.'.$help) }}</small>
        </article>
    @endforeach
</section>

<section class="insight-card">
    <div class="insight-head">
        <div>
            <h2>{{ __('admin.traffic.over_time') }}</h2>
            <p>{{ __('admin.traffic.over_time_help') }}</p>
        </div>
        <div class="insight-legend">
            <span class="is-pages">{{ __('admin.traffic.pages') }}</span>
            <span class="is-products">{{ __('admin.traffic.products') }}</span>
            <span class="is-orders">{{ __('admin.traffic.orders') }}</span>
        </div>
    </div>
    @include('filament.admin.partials.traffic-bars', ['rows' => $report['series']['rows'], 'tall' => true])
</section>

<section class="insight-split">
    @if($report['bucket'] !== 'hour')
        <article class="insight-card">
            <h2>{{ __('admin.traffic.by_hour') }}</h2>
            <p>{{ __('admin.traffic.by_hour_help') }}</p>
            @include('filament.admin.partials.traffic-bars', ['rows' => $report['by_hour'], 'tall' => false])
        </article>
    @endif
    <article class="insight-card">
        <h2>{{ __('admin.traffic.by_weekday') }}</h2>
        <p>{{ __('admin.traffic.by_weekday_help') }}</p>
        @include('filament.admin.partials.traffic-bars', ['rows' => $report['by_weekday'], 'tall' => false])
    </article>
</section>

<section class="insight-lists">
    @include('filament.admin.partials.traffic-list', ['title' => __('admin.traffic.places'), 'help' => __('admin.traffic.places_help'), 'rows' => $report['places']])
    @include('filament.admin.partials.traffic-list', ['title' => __('admin.traffic.devices'), 'help' => __('admin.traffic.devices_help'), 'rows' => $report['devices']])
    @include('filament.admin.partials.traffic-list', ['title' => __('admin.traffic.referrers'), 'help' => __('admin.traffic.referrers_help'), 'rows' => $report['referrers']])
    @include('filament.admin.partials.traffic-list', ['title' => __('admin.traffic.paths'), 'help' => __('admin.traffic.paths_help'), 'rows' => $report['paths']])
</section>
