<div class="overflow-hidden rounded-xl bg-white shadow-sm dark:bg-gray-900">
    <table class="w-full text-sm">
        <thead>
            <tr>
                <th class="p-3 text-start">{{ __('admin.fields.reference') }}</th>
                <th class="p-3 text-start">{{ __('admin.fields.customer') }}</th>
                <th class="p-3 text-start">{{ __('admin.fields.status') }}</th>
                <th class="p-3 text-start">{{ __('admin.fields.total') }}</th>
            </tr>
        </thead>
        <tbody>
            @foreach($orders as $order)
                <tr class="cursor-pointer border-t" onclick="window.location='{{ $orderUrl($order) }}'">
                    <td class="p-3"><a href="{{ $orderUrl($order) }}">{{ $order->reference }}</a></td>
                    <td class="p-3">{{ $order->customer_name }}</td>
                    <td class="p-3">{{ $order->status }}</td>
                    <td class="p-3">{{ $order->total }}</td>
                </tr>
            @endforeach
        </tbody>
    </table>
</div>
