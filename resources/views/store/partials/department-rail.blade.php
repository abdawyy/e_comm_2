@php
    $rail = collect();
    try {
        if (\Illuminate\Support\Facades\Schema::hasColumn('categories', 'icon_path')) {
            $rail = \App\Models\Category::query()->where('is_active', true)->whereNotNull('icon_path')->orderBy('sort_order')->get();
        }
    } catch (\Throwable) {
        $rail = collect();
    }
@endphp
@if($rail->isNotEmpty())
    <nav class="dept-rail" aria-label="{{ __('store.home.categories') }}">
        @foreach($rail as $department)
            @if($icon = $department->iconUrl())
                <a href="{{ $department->destination() }}">
                    <img src="{{ $icon }}" alt="">
                    <span>{{ $department->name }}</span>
                </a>
            @endif
        @endforeach
    </nav>
    <style>
        .dept-rail { display: none; }
        @media (max-width: 800px) {
            .dept-rail {
                display: flex;
                flex-direction: column;
                position: fixed;
                top: 38%;
                z-index: 40;
                filter: drop-shadow(0 8px 16px rgba(0, 0, 0, .18));
            }
            html[dir="rtl"] .dept-rail { right: 0; align-items: flex-end; }
            html[dir="ltr"] .dept-rail { left: 0; align-items: flex-start; }
            .dept-rail a {
                display: flex;
                align-items: center;
                width: 46px;
                height: 46px;
                overflow: hidden;
                background: #131316;
                color: #ffffff;
                text-decoration: none;
                transition: width .28s ease;
            }
            .dept-rail img { width: 46px; height: 46px; object-fit: cover; flex: none; }
            .dept-rail span { padding-inline: 0.8rem; white-space: nowrap; font-weight: 700; font-size: 0.82rem; }
            html[dir="ltr"] .dept-rail a:hover,
            html[dir="ltr"] .dept-rail a:focus { width: 11.5rem; }
            html[dir="rtl"] .dept-rail a { flex-direction: row-reverse; }
            html[dir="rtl"] .dept-rail a:hover,
            html[dir="rtl"] .dept-rail a:focus { width: 11.5rem; }
        }
    </style>
@endif
