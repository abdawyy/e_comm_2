@php
    $steps = ['pending', 'confirmed', 'processing', 'shipped', 'delivered'];
    $current = array_search($order->status, $steps, true);
    $cancelled = $order->status === 'cancelled';
@endphp
<ol class="store-track">
    @foreach($steps as $index => $step)
        <li @class(['is-done' => ! $cancelled && $current !== false && $index <= $current, 'is-current' => ! $cancelled && $current === $index])>
            <span>{{ __('store.track.steps.'.$step) }}</span>
        </li>
    @endforeach
    @if($cancelled)
        <li class="is-current is-cancelled"><span>{{ __('store.track.steps.cancelled') }}</span></li>
    @endif
</ol>
<style>
    .store-track { display: flex; flex-wrap: wrap; gap: 0.5rem; list-style: none; margin: 1rem 0; padding: 0; }
    .store-track li { border: 1px solid var(--color-border, #e2e8f0); border-radius: 999px; padding: 0.35rem 0.75rem; font-size: 0.85rem; }
    .store-track li.is-done { background: var(--color-brand, #16a34a); color: white; border-color: transparent; }
    .store-track li.is-cancelled { background: var(--color-danger, #ef4444); color: white; border-color: transparent; }
</style>
