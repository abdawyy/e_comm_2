@php($maxRevenue = 0)
@foreach($series as $row)
    @php($maxRevenue = max($maxRevenue, (float) $row['revenue']))
@endforeach
<div class="insight">
    <section class="insight-card">
        <div class="insight-head">
            <div>
                <h2>{{ __('admin.pages.sales') }}</h2>
                <p>{{ __('admin.sales.note') }}</p>
            </div>
        </div>
        <div class="insight-chips">
            @foreach($options as $option)
                <button type="button" wire:click="setPeriod('{{ $option }}')" @class(['insight-chip', 'is-on' => $period === $option])>{{ __('admin.periods.'.$option) }}</button>
            @endforeach
        </div>
    </section>

    <section class="insight-kpis">
        @foreach([
            ['revenue', $revenue],
            ['orders', $orders],
            ['average', $average],
            ['pending', $pending],
        ] as [$key, $value])
            <article class="insight-kpi">
                <p>{{ __('admin.stats.'.$key) }}</p>
                <strong>{{ $value }}</strong>
            </article>
        @endforeach
    </section>

    <section class="insight-card">
        <h2>{{ __('admin.stats.revenue_chart') }}</h2>
        @forelse($series as $row)
            <div class="sales-row">
                <span>{{ $row['label'] }}</span>
                <span class="sales-meter"><span style="width: {{ $maxRevenue > 0 ? max(4, ((float) $row['revenue'] / $maxRevenue) * 100) : 0 }}%"></span></span>
                <strong>{{ $math->format($row['revenue']) }}</strong>
                <small>{{ $row['orders'] }}</small>
            </div>
        @empty
            <p>{{ __('admin.stats.empty') }}</p>
        @endforelse
    </section>

    <section class="insight-split">
        <article class="insight-card">
            <h2>{{ __('admin.stats.top_products') }}</h2>
            <div class="insight-table-wrap">
                <table>
                    <tbody>
                        @forelse($products as $product)
                            <tr>
                                <td>{{ $product['name'] }}</td>
                                <td>{{ $math->format($product['revenue']) }}</td>
                            </tr>
                        @empty
                            <tr><td>{{ __('admin.stats.empty') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
        <article class="insight-card">
            <h2>{{ __('admin.sales.coupons') }}</h2>
            <div class="insight-table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>{{ __('admin.insights.coupon') }}</th>
                            <th>{{ __('admin.stats.orders') }}</th>
                            <th>{{ __('admin.stats.revenue') }}</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($coupons as $coupon)
                            <tr>
                                <td>{{ $coupon['code'] }}</td>
                                <td>{{ $coupon['orders'] }}</td>
                                <td>{{ $math->format($coupon['revenue']) }}</td>
                            </tr>
                        @empty
                            <tr><td colspan="3">{{ __('admin.sales.no_coupons') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>

    <section class="insight-split">
        <article class="insight-card">
            <h2>{{ __('admin.stats.by_city') }}</h2>
            <div class="insight-table-wrap">
                <table>
                    <tbody>
                        @forelse($cities as $city)
                            <tr>
                                <td>{{ $city['city'] }}</td>
                                <td>{{ $city['orders'] }}</td>
                                <td>{{ $math->format($city['revenue']) }}</td>
                            </tr>
                        @empty
                            <tr><td>{{ __('admin.stats.empty') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
        <article class="insight-card">
            <h2>{{ __('admin.stats.by_payment') }}</h2>
            <div class="insight-table-wrap">
                <table>
                    <tbody>
                        @forelse($payments as $gateway => $count)
                            @php($label = __('store.payment.'.$gateway))
                            <tr>
                                <td>{{ $label === 'store.payment.'.$gateway ? $gateway : $label }}</td>
                                <td>{{ $count }}</td>
                            </tr>
                        @empty
                            <tr><td>{{ __('admin.stats.empty') }}</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </article>
    </section>
</div>
@include('filament.admin.partials.insight-styles')
