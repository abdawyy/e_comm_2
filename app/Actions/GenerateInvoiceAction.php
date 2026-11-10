<?php

namespace App\Actions;

use App\Models\DesignAsset;
use App\Models\Order;
use App\Models\SiteTheme;
use App\Services\DecimalMath;
use Mpdf\Mpdf;

class GenerateInvoiceAction
{
    public function __construct(private DecimalMath $math) {}

    public function execute(Order $order): string
    {
        $order->loadMissing('items');
        $theme = SiteTheme::current();
        $logo = DesignAsset::urlFor('logo');
        $locale = in_array($order->locale, ['en', 'ar'], true) ? $order->locale : 'ar';
        $previous = app()->getLocale();
        app()->setLocale($locale);

        $html = view('pdf.invoice', [
            'order' => $order,
            'theme' => $theme,
            'logo' => $logo,
            'math' => $this->math,
            'locale' => $locale,
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

        return $mpdf->Output('', 'S');
    }
}
