<div class="space-y-3 rounded-xl p-4" style="background: {{ $canvas ?: 'transparent' }}">
    <div class="flex items-center gap-3 rounded-xl p-3" style="background: {{ $surface }}; border: 1px solid {{ $border }}">
        <span class="rounded-full px-3 py-2 text-white" style="background: {{ $primary }}">{{ __('admin.theme.sample_button') }}</span>
        <span class="rounded-full px-3 py-1" style="background: {{ $accent }}; color: white">{{ __('admin.theme.sample_badge') }}</span>
        <span style="color: {{ $primary }}">{{ __('admin.theme.sample_nav') }}</span>
    </div>
</div>
