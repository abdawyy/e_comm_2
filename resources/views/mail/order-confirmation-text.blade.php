{{ $theme->brand_name }}

{{ __('store.mail.confirmed_heading', ['reference' => $order->reference], $locale) }}
{{ $order->customer_name }}

{{ __('store.mail.confirmed_body', [], $locale) }}

@foreach($order->items as $item)
{{ $item->localizedName($locale) }} · {{ $item->quantity }} {{ $item->unit }} · {{ $math->format((string) $item->line_subtotal) }}
@endforeach

{{ __('store.checkout.total', [], $locale) }}: {{ $math->format((string) $order->total) }}

{{ __('store.mail.pdf_attached', [], $locale) }}
