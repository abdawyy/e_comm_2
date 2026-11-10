@php($announcement = \App\Models\StoreSetting::announcement())
@if($announcement)
    <div class="store-announce">
        <div class="store-announce-track">
            @for($copy = 0; $copy < 8; $copy++)
                <span>{{ $announcement }}</span>
            @endfor
        </div>
    </div>
@endif
