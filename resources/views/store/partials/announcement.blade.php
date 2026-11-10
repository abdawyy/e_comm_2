@php($announcement = \App\Models\StoreSetting::announcement())
@if($announcement)
    <style>
        .store-announce {
            background: #eeddb0 !important;
            color: #000 !important;
            overflow: hidden;
            position: relative;
            height: 45px;
            box-shadow: 0 2px 6px rgba(0, 0, 0, .08);
        }
        .store-announce-track {
            display: flex;
            align-items: center;
            height: 100%;
            white-space: nowrap;
            position: absolute;
            animation: store-announce 20s linear infinite;
        }
        .store-announce-track span {
            display: inline-flex;
            align-items: center;
            height: 100%;
            padding-inline-end: 60px;
            font-size: 16px;
            font-weight: 500;
            color: #000 !important;
        }
        .store-announce-track svg {
            width: 18px;
            height: 18px;
            margin-inline-end: 10px;
            fill: currentColor;
            flex: none;
        }
        html[dir="rtl"] .store-announce-track { animation-name: store-announce-rtl; }
        @keyframes store-announce {
            from { left: 100%; }
            to { left: -100%; }
        }
        @keyframes store-announce-rtl {
            from { right: 100%; }
            to { right: -100%; }
        }
    </style>
    <div class="store-announce" style="background:#eeddb0;color:#000;overflow:hidden;position:relative;height:45px;">
        <div class="store-announce-track">
            @for($copy = 0; $copy < 8; $copy++)
                <span>
                    <svg viewBox="0 0 640 512" aria-hidden="true"><path d="M48 0C21.5 0 0 21.5 0 48V368c0 26.5 21.5 48 48 48H64c0 53 43 96 96 96s96-43 96-96H384c0 53 43 96 96 96s96-43 96-96h32c17.7 0 32-14.3 32-32s-14.3-32-32-32V288 256 237.3c0-17-6.7-33.3-18.7-45.3L512 114.7c-12-12-28.3-18.7-45.3-18.7H416V48c0-26.5-21.5-48-48-48H48zM416 160h50.7L544 237.3V256H416V160zM160 368a48 48 0 1 1 0 96 48 48 0 1 1 0-96zm368 48a48 48 0 1 1-96 0 48 48 0 1 1 96 0z"/></svg>
                    {{ $announcement }}
                </span>
            @endfor
        </div>
    </div>
@endif
