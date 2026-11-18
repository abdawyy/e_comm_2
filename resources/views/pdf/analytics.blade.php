<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="utf-8">
    <style>
        body { font-family: dejavusans, sans-serif; font-size: 11px; color: #1c1c1c; }
        h1 { font-size: 16px; margin: 0 0 8px; }
        h2 { font-size: 13px; margin: 14px 0 6px; }
        table { width: 100%; border-collapse: collapse; }
        th, td { border-bottom: 1px solid #ddd; padding: 4px 6px; text-align: left; vertical-align: top; }
        th { background: #131316; color: #fff; }
    </style>
</head>
<body>
    <h1>{{ $title }}</h1>
    @foreach($sections as $section)
        <h2>{{ $section['title'] }}</h2>
        <table>
            <thead>
                <tr>
                    <th>{{ __('admin.actions.export') }}</th>
                    <th>Value</th>
                </tr>
            </thead>
            <tbody>
                @foreach($section['rows'] as $row)
                    <tr>
                        <td>{{ $row['label'] }}</td>
                        <td>{{ $row['value'] }}</td>
                    </tr>
                @endforeach
            </tbody>
        </table>
    @endforeach
</body>
</html>
