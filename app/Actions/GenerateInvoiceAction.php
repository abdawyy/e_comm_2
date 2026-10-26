<?php

namespace App\Actions;

use App\Models\DesignAsset;
use App\Models\Order;
use App\Models\SiteTheme;
use App\Services\DecimalMath;
use Illuminate\Support\Facades\Storage;
use Mpdf\Mpdf;

class GenerateInvoiceAction
{
    public function __construct(private DecimalMath $math) {}

    public function execute(Order $order): string
    {
        $order->loadMissing('items');
        $theme = SiteTheme::current();
        $logo = DesignAsset::urlFor('logo');
        $locale = $order->locale ?: 'en';
        $previous = app()->getLocale();
        app()->setLocale($locale);

        $html = view('pdf.invoice', [
            'order' => $order,
            'theme' => $theme,
            'logo' => $logo,
            'math' => $this->math,
        ])->render();

        app()->setLocale($previous);

        $directory = storage_path('app/mpdf');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $mpdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A4',
            'default_font' => 'dejavusans',
            'tempDir' => $directory,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);
        $mpdf->WriteHTML($html);

        $path = 'invoices/'.$order->reference.'.pdf';
        Storage::disk('local')->put($path, $mpdf->Output('', 'S'));
        $order->forceFill(['invoice_path' => $path])->save();

        return $path;
    }
}
