<div class="insight">
    @include('filament.admin.partials.insight-board')

    <section class="insight-card">
        <div class="insight-head">
            <div>
                <h2>{{ __('admin.pages.customers') }}</h2>
                <p>{{ __('admin.customers.place_note') }}</p>
            </div>
        </div>

        @forelse($customers as $customer)
            <article class="insight-person">
                <header>
                    <span class="insight-avatar">{{ mb_substr($customer['name'], 0, 1) }}</span>
                    <div>
                        <h3><a href="{{ \App\Filament\Admin\Resources\UserResource::getUrl('view', ['record' => $customer['id']]) }}">{{ $customer['name'] }}</a></h3>
                        <p>{{ $customer['email'] }} @if($customer['phone']) · {{ $customer['phone'] }} @endif</p>
                    </div>
                    <div class="insight-pills">
                        <span>{{ __('admin.insights.orders_short') }} {{ $customer['orders_count'] }}</span>
                        <span>{{ $customer['spent'] }}</span>
                        <span>{{ __('admin.insights.sessions') }} {{ $customer['sessions'] }}</span>
                    </div>
                </header>
                <dl>
                    <div><dt>{{ __('admin.insights.locations') }}</dt><dd>{{ $customer['locations'] !== [] ? implode(', ', $customer['locations']) : '—' }}</dd></div>
                    <div><dt>{{ __('admin.insights.devices') }}</dt><dd>{{ collect($customer['devices'])->map(fn ($count, $device) => $device.': '.$count)->implode(', ') ?: '—' }}</dd></div>
                    <div><dt>{{ __('admin.insights.browse_hour') }}</dt><dd>{{ $customer['browse_hour'] ?? '—' }}</dd></div>
                    <div><dt>{{ __('admin.insights.purchase_hour') }}</dt><dd>{{ $customer['purchase_hour'] ?? '—' }}</dd></div>
                    <div><dt>{{ __('admin.insights.viewed') }}</dt><dd>{{ collect($customer['viewed'])->pluck('name')->implode(', ') ?: '—' }}</dd></div>
                    <div><dt>{{ __('admin.insights.purchased') }}</dt><dd>{{ collect($customer['purchased'])->pluck('name')->implode(', ') ?: '—' }}</dd></div>
                </dl>
                <div class="insight-table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('admin.fields.reference') }}</th>
                                <th>{{ __('admin.fields.status') }}</th>
                                <th>{{ __('admin.fields.total') }}</th>
                                <th>{{ __('admin.customers.place') }}</th>
                                <th>{{ __('admin.fields.address') }}</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($customer['orders'] as $order)
                                <tr>
                                    <td><a href="{{ \App\Filament\Admin\Resources\OrderResource::getUrl('view', ['record' => $order['id']]) }}">{{ $order['reference'] }}</a></td>
                                    <td>{{ __('admin.status.'.$order['status']) }}</td>
                                    <td>{{ $order['total'] }}</td>
                                    <td>{{ $order['place'] ?: '—' }}</td>
                                    <td>{{ $order['address'] ?: '—' }}</td>
                                </tr>
                            @empty
                                <tr><td colspan="5">{{ __('admin.customers.no_orders') }}</td></tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </article>
        @empty
            <p class="insight-empty">{{ __('admin.customers.no_customers') }}</p>
        @endforelse

        @if($guests !== [])
            <h2 class="insight-subtitle">{{ __('admin.customers.guests') }}</h2>
            @foreach($guests as $guest)
                <article class="insight-person">
                    <header>
                        <span class="insight-avatar">{{ mb_substr($guest['name'], 0, 1) }}</span>
                        <div>
                            <h3>{{ $guest['name'] }}</h3>
                            <p>{{ $guest['phone'] }}</p>
                        </div>
                    </header>
                    <div class="insight-table-wrap">
                        <table>
                            <tbody>
                                @foreach($guest['orders'] as $order)
                                    <tr>
                                        <td><a href="{{ \App\Filament\Admin\Resources\OrderResource::getUrl('view', ['record' => $order['id']]) }}">{{ $order['reference'] }}</a></td>
                                        <td>{{ __('admin.status.'.$order['status']) }}</td>
                                        <td>{{ $order['total'] }}</td>
                                        <td>{{ $order['place'] ?: $order['address'] }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </article>
            @endforeach
        @endif
    </section>

    <section class="insight-card">
        <h2>{{ __('admin.customers.visitors') }}</h2>
        <div class="insight-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('admin.customers.place') }}</th>
                        <th>{{ __('admin.traffic.device') }}</th>
                        <th>{{ __('admin.fields.name') }}</th>
                        <th>{{ __('admin.insights.last_visit') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($visitors as $visitor)
                        <tr>
                            <td>{{ $visitor['location'] ?: '—' }}</td>
                            <td>{{ $visitor['device'] ?: '—' }}</td>
                            <td>{{ $visitor['name'] ?: '—' }}</td>
                            <td>{{ optional($visitor['last_seen_at'])->toDateTimeString() }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">{{ __('admin.customers.no_places') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
</div>
@include('filament.admin.partials.insight-styles')
