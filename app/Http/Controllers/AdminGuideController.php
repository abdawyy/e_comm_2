<?php

namespace App\Http\Controllers;

use Illuminate\Http\Response;
use Mpdf\Mpdf;

class AdminGuideController extends Controller
{
    public function __invoke(): Response
    {
        abort_unless(auth()->user()?->can('View:AdminGuide'), 403);

        $locale = app()->getLocale() === 'ar' ? 'ar' : 'en';
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
        $pdf->WriteHTML(view('pdf.guide', ['locale' => $locale])->render());

        return response($pdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'attachment; filename="staff-guide.pdf"',
        ]);
    }
}
