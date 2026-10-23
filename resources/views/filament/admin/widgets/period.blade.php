<div class="flex flex-wrap gap-2">
    @foreach($this->options() as $option)
        <button type="button" wire:click="setPeriod('{{ $option }}')" @class(['rounded-full px-3 py-1 text-sm', 'bg-primary-600 text-white' => $this->current() === $option, 'bg-gray-100 dark:bg-gray-800' => $this->current() !== $option])>
            {{ __('admin.periods.'.$option) }}
        </button>
    @endforeach
</div>
