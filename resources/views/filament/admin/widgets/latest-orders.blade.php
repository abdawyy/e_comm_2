<div class="overflow-hidden rounded-xl bg-white shadow-sm ring-1 ring-gray-950/5 dark:bg-gray-900 dark:ring-white/10">
    <table class="w-full text-sm text-gray-950 dark:text-gray-100">
        <thead>
            <tr class="text-gray-500 dark:text-gray-400">
                <th class="p-3 text-start font-medium">{{ __('admin.fields.reference') }}</th>
                <th class="p-3 text-start font-medium">{{ __('admin.fields.customer') }}</th>
                <th class="p-3 text-start font-medium">{{ __('admin.fields.status') }}</th>
                <th class="p-3 text-start font-medium">{{ __('admin.fields.total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                @php($statusLabel = __('admin.status.'.$order->status))
                <tr class="cursor-pointer border-t border-gray-100 hover:bg-gray-50 dark:border-white/10 dark:hover:bg-white/5" onclick="window.location='{{ $orderUrl($order) }}'">
                    <td class="p-3"><a class="font-medium" href="{{ $orderUrl($order) }}">{{ $order->reference }}</a></td>
                    <td class="p-3">{{ $order->customer_name }}</td>
                    <td class="p-3">{{ $statusLabel === 'admin.status.'.$order->status ? $order->status : $statusLabel }}</td>
                    <td class="p-3">{{ app(\App\Services\DecimalMath::class)->format((string) $order->total) }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
