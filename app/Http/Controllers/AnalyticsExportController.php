<?php

namespace App\Http\Controllers;

use App\Exports\AnalyticsSheetExport;
use App\Services\AnalyticsWorkbook;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Maatwebsite\Excel\Facades\Excel;
use Mpdf\Mpdf;
use Symfony\Component\HttpFoundation\BinaryFileResponse;

class AnalyticsExportController extends Controller
{
    public function __invoke(Request $request, string $report, string $format, AnalyticsWorkbook $workbook): Response|BinaryFileResponse
    {
        abort_unless($request->user()?->is_admin, 403);
        abort_unless(in_array($report, ['sales', 'traffic', 'customers', 'delivery', 'all'], true), 404);
        abort_unless(in_array($format, ['xlsx', 'pdf'], true), 404);

        $sections = $workbook->sections($report);
        $filename = 'analytics-'.$report;

        if ($format === 'xlsx') {
            return Excel::download(new AnalyticsSheetExport($sections, $filename), $filename.'.xlsx');
        }

        $html = view('pdf.analytics', [
            'title' => str_replace('-', ' ', ucfirst($filename)),
            'sections' => $sections,
        ])->render();

        $directory = storage_path('app/mpdf');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'tempDir' => $directory,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);
        $pdf->WriteHTML($html);

        return response($pdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="'.$filename.'.pdf"',
        ]);
    }
}
