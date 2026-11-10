<div class="insight">
    <section class="insight-card">
        <h2>{{ __('admin.pages.delivery') }}</h2>
        <p>{{ __('store.delivery.note') }}</p>
        <p>{{ __('store.delivery.unassigned') }}: {{ $unassigned }}</p>
    </section>
    <section class="insight-card" data-delivery-box>
        <h2>{{ __('store.delivery.waiting') }}</h2>
        <label class="delivery-search">
            <span>{{ __('store.delivery.search') }}</span>
            <input type="search" placeholder="{{ __('store.delivery.search') }}">
        </label>
        <div class="insight-table-wrap">
            <table>
                <thead>
                    <tr>
                        <th>{{ __('admin.fields.reference') }}</th>
                        <th>{{ __('admin.fields.customer') }}</th>
                        <th>{{ __('admin.fields.city') }}</th>
                    </tr>
                </thead>
                <tbody>
                    @forelse($waiting as $order)
                        <tr data-search="{{ $order->reference }} {{ $order->customer_name }} {{ $order->city_name }}">
                            <td>{{ $order->reference }}</td>
                            <td>{{ $order->customer_name }}</td>
                            <td>{{ $order->city_name }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="3">{{ __('admin.stats.empty') }}</td></tr>
                    @endforelse
                    <tr data-none hidden><td colspan="3">{{ __('store.delivery.no_match') }}</td></tr>
                </tbody>
            </table>
        </div>
    </section>
    <section class="insight-card" data-delivery-box>
        <h2>{{ __('store.delivery.analysis') }}</h2>
        <label class="delivery-search">
            <span>{{ __('store.delivery.search') }}</span>
            <input type="search" placeholder="{{ __('store.delivery.search') }}">
        </label>
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
                        <tr data-search="{{ $row['name'] }} {{ $row['orders'] }} {{ $row['fees'] }}">
                            <td>{{ $row['name'] }}</td>
                            <td>{{ $row['orders'] }}</td>
                            <td>{{ $row['fee_orders'] }}</td>
                            <td>{{ $row['fees'] }}</td>
                        </tr>
                    @empty
                        <tr><td colspan="4">{{ __('admin.stats.empty') }}</td></tr>
                    @endforelse
                    <tr data-none hidden><td colspan="4">{{ __('store.delivery.no_match') }}</td></tr>
                </tbody>
            </table>
        </div>
    </section>
    <section class="insight-card">
        <h2>{{ __('store.delivery.print') }}</h2>
        <p>{{ __('store.delivery.print_only') }}</p>
        @forelse($groups as $group)
            <div data-delivery-box>
                <h3>{{ $group['name'] }}</h3>
                <label class="delivery-search">
                    <span>{{ __('store.delivery.search') }}</span>
                    <input type="search" placeholder="{{ __('store.delivery.search') }}">
                </label>
                <div class="insight-table-wrap">
                    <table>
                        <thead>
                            <tr>
                                <th>{{ __('admin.fields.reference') }}</th>
                                <th>{{ __('admin.fields.customer') }}</th>
                                <th>{{ __('store.delivery.fee') }}</th>
                                <th></th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($group['orders'] as $order)
                                <tr data-search="{{ $order->reference }} {{ $order->customer_name }}">
                                    <td>{{ $order->reference }}</td>
                                    <td>{{ $order->customer_name }}</td>
                                    <td>{{ $order->shipping_label_fee !== null ? $math->format((string) $order->shipping_label_fee) : '—' }}</td>
                                    <td><a href="{{ route('delivery.label', $order) }}" target="_blank">{{ __('store.delivery.print') }}</a></td>
                                </tr>
                            @endforeach
                            <tr data-none hidden><td colspan="4">{{ __('store.delivery.no_match') }}</td></tr>
                        </tbody>
                    </table>
                </div>
            </div>
        @empty
            <p>{{ __('admin.stats.empty') }}</p>
        @endforelse
    </section>
</div>
@include('filament.admin.partials.insight-styles')
<style>
    .delivery-search { display: grid; gap: 0.3rem; margin: 0.75rem 0 0.4rem; font-size: 0.78rem; font-weight: 700; }
    .delivery-search input {
        width: 100%;
        box-sizing: border-box;
        border: 1px solid rgb(226 232 240);
        border-radius: 0.75rem;
        background: rgb(248 250 252);
        padding: 0.55rem 0.7rem;
        font-weight: 500;
    }
    .dark .delivery-search input { border-color: rgb(55 65 81); background: rgb(15 23 42); color: inherit; }
    [data-delivery-box] + [data-delivery-box] { margin-top: 1rem; }
</style>
<script>
    document.querySelectorAll('[data-delivery-box]').forEach((box) => {
        const input = box.querySelector('input[type="search"]');
        if (!input) return;
        input.addEventListener('input', () => {
            const query = input.value.trim().toLowerCase();
            const rows = box.querySelectorAll('tbody tr[data-search]');
            let shown = 0;
            rows.forEach((row) => {
                const match = query === '' || (row.dataset.search || '').toLowerCase().includes(query);
                row.hidden = !match;
                if (match) shown++;
            });
            const empty = box.querySelector('tr[data-none]');
            if (empty) empty.hidden = query === '' || shown > 0;
        });
    });
</script>
