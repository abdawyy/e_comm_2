<div class="insight">
    <section class="insight-card">
        <h2>{{ __('admin.pages.delivery') }}</h2>
        <p>{{ __('store.delivery.note') }}</p>
        <p>{{ __('store.delivery.unassigned') }}: {{ $unassigned }}</p>
    </section>
    <section class="insight-card">
        <h2>{{ __('store.delivery.analysis') }}</h2>
        <div class="insight-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('store.delivery.carrier') }}</th>
                        <th>{{ __('admin.stats.orders') }}</th>
                        <th>{{ __('store.delivery.fee_orders') }}</th>
                        <th>{{ __('store.delivery.fees') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($rows as $row)
                        <tr>
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['orders'] }}</td>
                            <td>{{ $row['fee_orders'] }}</td>
                            <td>{{ $row['fees'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">{{ __('admin.stats.empty') }}</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
    </section>
    <section class="insight-card">
        <h2>{{ __('store.delivery.assigned') }}</h2>
        <div class="insight-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('admin.fields.reference') }}</th>
                        <th>{{ __('admin.fields.customer') }}</th>
                        <th>{{ __('store.delivery.carrier') }}</th>
                        <th>{{ __('store.delivery.fee') }}</th>
                        <th></th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($recent as $order)
                        <tr>
                            <td>{{ $order->reference }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->shippingCarrier?->name }}</td>
                            <td>{{ $order->shipping_label_fee !== null ? $math->format((string) $order->shipping_label_fee) : '—' }}</td>
                            <td><a href="{{ route('delivery.label', $order) }}" target="_blank">{{ __('store.delivery.print') }}</a></td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </section>
</div>
@include('filament.admin.partials.insight-styles')
