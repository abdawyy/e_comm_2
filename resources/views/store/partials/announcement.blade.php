@php($announcement = \App\Models\StoreSetting::announcement())
@if($announcement)
    @php($announceBg = \App\Models\StoreSetting::announcementBackground())
    <style>
        .store-announce {
            background: var(--announce-bg) !important;
            color: #131316 !important;
            overflow: hidden;
            position: relative;
            height: 45px;
        }
        .store-announce-track {
            display: flex;
            align-items: center;
            width: max-content;
            height: 100%;
            white-space: nowrap;
            animation: store-announce 32s linear infinite;
            will-change: transform;
        }
        .store-announce-track span {
            display: inline-flex;
            align-items: center;
            height: 100%;
            padding-inline-end: 4rem;
            font-size: 16px;
            font-weight: 500;
            color: #131316 !important;
            background: transparent !important;
        }
        html[dir="rtl"] .store-announce-track { animation-direction: reverse; }
        @keyframes store-announce {
            from { transform: translate3d(0, 0, 0); }
            to { transform: translate3d(-50%, 0, 0); }
        }
    </style>
    <div class="store-announce" style="--announce-bg: {{ $announceBg }}; background: {{ $announceBg }}; color: #131316;">
        <div class="store-announce-track">
            @for($copy = 0; $copy < 8; $copy++)
                <span>{{ $announcement }}</span>
            @endfor
        </div>
    </div>
@endif
