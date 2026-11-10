@php
    $invoice = \App\Models\StoreSetting::invoiceProfile();
    $text = [
        'tagline' => \App\Models\StoreSetting::invoiceLine($locale, 'tagline'),
        'phone' => \App\Models\StoreSetting::invoiceLine($locale, 'phone'),
        'tax_number' => \App\Models\StoreSetting::invoiceLine($locale, 'tax_number'),
        'title' => \App\Models\StoreSetting::invoiceLine($locale, 'title'),
        'tax' => \App\Models\StoreSetting::invoiceLine($locale, 'tax'),
        'thanks' => \App\Models\StoreSetting::invoiceLine($locale, 'thanks'),
        'policy' => \App\Models\StoreSetting::invoiceLine($locale, 'policy'),
    ];
    $when = $order->created_at;
    $gateway = (string) $order->payment_gateway;
    $payment = match ($gateway) {
        'cod' => __('store.payment.cod', [], $locale),
        'paymob', 'visa' => __('store.payment.visa', [], $locale),
        default => $gateway,
    };
    $statusKey = 'admin.status.'.$order->status;
    $status = __($statusKey, [], $locale);
    if ($status === $statusKey) {
        $status = (string) $order->status;
    }
    $site = trim((string) $invoice['contact']) !== '' ? $invoice['contact'] : (parse_url((string) config('app.url'), PHP_URL_HOST) ?: config('app.url'));
    $taxAmount = $invoice['show_tax'] && $math->comp($invoice['tax_percent'], '0') === 1
        ? $math->percent((string) $order->subtotal, (string) $invoice['tax_percent'])
        : '0';
    $rows = [
        __('store.invoice.subtotal', [], $locale) => $math->format((string) $order->subtotal),
        __('store.invoice.shipping', [], $locale) => $math->format((string) $order->shipping_fee),
        __('store.invoice.discount', [], $locale) => $math->format((string) $order->discount),
    ];
    if (bccomp((string) ($order->order_packaging_fee ?? '0'), '0', 4) === 1) {
        $rows[__('store.invoice.packaging', [], $locale)] = $math->format((string) $order->order_packaging_fee);
    }
    if ($invoice['show_tax']) {
        $rows[$text['tax']] = $math->format($taxAmount);
    }
    $light = function (string $hex): bool {
        $hex = ltrim(strtolower($hex), '#');
        if (strlen($hex) !== 6 || ! ctype_xdigit($hex)) {
            return false;
        }
        $red = hexdec(substr($hex, 0, 2));
        $green = hexdec(substr($hex, 2, 2));
        $blue = hexdec(substr($hex, 4, 2));

        return ((0.299 * $red) + (0.587 * $green) + (0.114 * $blue)) >= 186;
    };
    $ink = $light($invoice['titles']) ? '#2b2118' : $invoice['titles'];
    $barText = strtolower((string) $invoice['bar_text']) === '#ffffff' ? '#f7f7f7' : $invoice['bar_text'];
@endphp
<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: dejavusans, sans-serif; color: #2b2118; font-size: 11px; background: {{ $invoice['paper'] }}; }
        table { width: 100%; border-collapse: collapse; }
        .frame { border: 1.4px solid {{ $invoice['border'] }}; background: {{ $invoice['paper'] }}; }
        .brand { font-size: 18px; font-weight: bold; color: {{ $ink }}; }
        .muted { color: #6d5b4e; font-size: 10px; }
        .pill { background: {{ $invoice['accent'] }}; color: {{ $barText }}; font-weight: bold; font-size: 13px; padding: 7px 12px; }
        .card { background: {{ $invoice['card'] }}; }
        .head { background: {{ $invoice['accent'] }}; color: {{ $barText }}; font-weight: bold; }
        .line { border-bottom: 1px solid #e4d9cc; }
        .total { background: {{ $invoice['accent'] }}; color: {{ $barText }}; font-weight: bold; }
        .qr { border: 1px dashed {{ $invoice['border'] }}; color: #6d5b4e; text-align: center; }
        td, th { padding: 6px 8px; vertical-align: top; }
    </style>
</head>
<body @if($locale === 'ar') dir="rtl" @endif>
<table class="frame" height="250mm">
    <tr>
        <td style="padding: 16px 16px 8px;">
            <table>
                <tr>
                    <td width="58%">
                        <div class="brand" style="color: {{ $ink }}; background: {{ $invoice['paper'] }};">{{ $theme->brand_name }}</div>
                        <div class="muted">{{ $text['tagline'] }}</div>
                        @if(trim((string) ($invoice[$locale]['address'] ?? '')) !== '')
                            <div>{{ $invoice[$locale]['address'] }}</div>
                        @endif
                        @if(trim((string) $invoice['seller_phone']) !== '')
                            <div>{{ $text['phone'] }}: {{ $invoice['seller_phone'] }}</div>
                        @endif
                        @if(trim((string) $invoice['seller_tax']) !== '')
                            <div>{{ $text['tax_number'] }}: {{ $invoice['seller_tax'] }}</div>
                        @endif
                    </td>
                    <td width="42%" align="{{ $locale === 'ar' ? 'left' : 'right' }}">
                        <div class="pill" style="color: {{ $barText }}; background: {{ $invoice['accent'] }};">{{ $text['title'] }}</div>
                        <div style="margin-top: 8px;"><strong>{{ __('store.invoice.number', [], $locale) }}:</strong> {{ $order->reference }}</div>
                        <div><strong>{{ __('store.invoice.date', [], $locale) }}:</strong> {{ $when?->format('d/m/Y') }}</div>
                        <div><strong>{{ __('store.invoice.time', [], $locale) }}:</strong> {{ $when?->format('H:i') }}</div>
                    </td>
                </tr>
            </table>
            <table style="margin-top: 12px;">
                <tr>
                    <td class="card" width="48%" style="padding: 10px;">
                        <div style="font-weight: bold; text-align: center; margin-bottom: 6px; color: {{ $ink }}; background: {{ $invoice['card'] }};">{{ __('store.invoice.customer_block', [], $locale) }}</div>
                        <div><strong>{{ __('store.invoice.customer', [], $locale) }}:</strong> {{ $order->customer_name }}</div>
                        <div><strong>{{ __('store.invoice.phone', [], $locale) }}:</strong> {{ $order->customer_phone }}</div>
                        <div><strong>{{ __('store.invoice.address', [], $locale) }}:</strong> {{ $order->city_name }} — {{ $order->address }}</div>
                    </td>
                    <td width="4%"></td>
                    <td class="card" width="48%" style="padding: 10px;">
                        <div style="font-weight: bold; text-align: center; margin-bottom: 6px; color: {{ $ink }}; background: {{ $invoice['card'] }};">{{ __('store.invoice.payment_block', [], $locale) }}</div>
                        <div><strong>{{ __('store.invoice.method', [], $locale) }}:</strong> {{ $payment }}</div>
                        <div><strong>{{ __('store.invoice.kind', [], $locale) }}:</strong> {{ __('store.invoice.delivery', [], $locale) }}</div>
                        @if(($forDelivery ?? false) || $order->shippingCarrier)
                            <div><strong>{{ __('store.delivery.carrier', [], $locale) }}:</strong> {{ $order->shippingCarrier?->getTranslation('name', $locale) }}</div>
                        @endif
                        <div><strong>{{ __('store.invoice.status', [], $locale) }}:</strong> {{ $status }}</div>
                    </td>
                </tr>
            </table>
            <table style="margin-top: 14px;">
                <thead>
                    <tr class="head">
                        <th align="center" width="6%" style="color: {{ $barText }}; background: {{ $invoice['accent'] }};">{{ __('store.invoice.hash', [], $locale) }}</th>
                        <th align="{{ $locale === 'ar' ? 'right' : 'left' }}" style="color: {{ $barText }}; background: {{ $invoice['accent'] }};">{{ __('store.invoice.item', [], $locale) }}</th>
                        <th align="{{ $locale === 'ar' ? 'right' : 'left' }}" style="color: {{ $barText }}; background: {{ $invoice['accent'] }};">{{ __('store.invoice.grind', [], $locale) }}</th>
                        <th align="center" width="10%" style="color: {{ $barText }}; background: {{ $invoice['accent'] }};">{{ __('store.invoice.qty', [], $locale) }}</th>
                        <th align="center" width="16%" style="color: {{ $barText }}; background: {{ $invoice['accent'] }};">{{ __('store.invoice.price', [], $locale) }}</th>
                        <th align="center" width="16%" style="color: {{ $barText }}; background: {{ $invoice['accent'] }};">{{ __('store.invoice.total', [], $locale) }}</th>
                    </tr>
                </thead>
                <tbody>
                @foreach($order->items as $index => $item)
                    @php
                        $bits = [];
                        foreach ((array) $item->services_snapshot as $service) {
                            $name = is_array($service) ? ($service['name'] ?? $service['label'] ?? null) : $service;
                            if (is_array($name)) {
                                $bits[] = (string) ($name[$locale] ?? $name['ar'] ?? $name['en'] ?? '');
                            } elseif (is_string($name) && $name !== '') {
                                $bits[] = $name;
                            }
                        }
                        $detail = trim(implode(' · ', array_filter($bits)));
                        if ($detail === '') {
                            $detail = (string) $item->unit;
                        }
                        $qty = rtrim(rtrim((string) $item->quantity, '0'), '.');
                    @endphp
                    <tr class="line">
                        <td align="center">{{ $index + 1 }}</td>
                        <td>{{ $item->localizedName($locale) }}</td>
                        <td>{{ $detail }}</td>
                        <td align="center">{{ $qty }}</td>
                        <td align="center">{{ $math->format((string) $item->unit_price) }}</td>
                        <td align="center">{{ $math->format((string) $item->line_subtotal) }}</td>
                    </tr>
                @endforeach
                </tbody>
            </table>
            <table style="margin-top: 12px;">
                <tr>
                    <td width="42%">
                        <table>
                            <tr>
                                @if($invoice['show_qr'])
                                    <td class="qr" width="34%" height="78">{{ __('store.invoice.qr', [], $locale) }}</td>
                                @endif
                                <td>
                                    <div><strong>{{ __('store.invoice.notes', [], $locale) }}:</strong></div>
                                    <div class="muted">{{ $order->notes ?: $text['thanks'] }}</div>
                                </td>
                            </tr>
                        </table>
                    </td>
                    <td width="8%"></td>
                    <td width="54%">
                        <table>
                            @foreach($rows as $label => $amount)
                                <tr class="line">
                                    <td align="{{ $locale === 'ar' ? 'right' : 'left' }}">{{ $label }}:</td>
                                    <td align="end">{{ $amount }}</td>
                                </tr>
                            @endforeach
                            <tr class="total">
                                <td width="58%" style="color: {{ $barText }}; background: {{ $invoice['accent'] }};">{{ __('store.invoice.grand', [], $locale) }}:</td>
                                <td width="42%" align="{{ $locale === 'ar' ? 'left' : 'right' }}" style="color: {{ $barText }}; background: {{ $invoice['accent'] }};">{{ $math->format((string) $order->total) }}</td>
                            </tr>
                        </table>
                    </td>
                </tr>
            </table>
            <div class="muted" style="margin-top: 14px; border-top: 1px dashed #c9b8a6; padding-top: 8px;">
                {{ $text['policy'] }}<br>
                {{ __('store.invoice.contact', [], $locale) }}: {{ $site }}
            </div>
        </td>
    </tr>
</table>
</body>
</html>
