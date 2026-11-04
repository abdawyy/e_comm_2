<!DOCTYPE html>
<html lang="{{ $locale }}">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: dejavusans, sans-serif; color: #1A2B28; font-size: 12px; }
        h1 { font-size: 22px; margin: 0 0 4px; }
        .kicker { color: #8C6A3D; font-size: 11px; letter-spacing: 1px; }
        .rule { height: 4px; background: #C9A55C; margin: 8px 0 16px; }
        h2 { font-size: 15px; color: #1A2B28; border-bottom: 1px solid #C9A55C; padding-bottom: 3px; margin: 16px 0 6px; }
        p, li { line-height: 1.45; }
        ul { margin-top: 4px; }
    </style>
</head>
<body @if($locale === 'ar') dir="rtl" @endif>
    <div class="kicker">ATLAS MARKET</div>
    <h1>{{ $locale === 'ar' ? 'دليل فريق العمل' : 'Staff guide' }}</h1>
    <div class="rule"></div>
    @include('filament.admin.partials.guide-body', ['locale' => $locale])
</body>
</html>
