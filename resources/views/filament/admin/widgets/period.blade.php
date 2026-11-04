<div class="dash-period">
    @foreach($this->options() as $option)
        <button type="button" wire:click="setPeriod('{{ $option }}')" @class(['dash-period-chip', 'is-on' => $this->current() === $option])>
            {{ __('admin.periods.'.$option) }}
        </button>
    @endforeach
</div>
<style>
    .dash-period { display: flex; flex-wrap: wrap; gap: 0.45rem; }
    .dash-period-chip {
        border: 0;
        border-radius: 999px;
        background: rgb(241 245 249);
        padding: 0.45rem 0.9rem;
        font-size: 0.82rem;
        font-weight: 700;
        color: inherit;
    }
    .dark .dash-period-chip { background: rgb(31 41 55); }
    .dash-period-chip.is-on { background: #1A2B28; color: #F5EFE6; }
</style>
