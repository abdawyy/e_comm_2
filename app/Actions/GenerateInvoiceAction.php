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
        $order->loadMissing(['items', 'shippingCarrier']);
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
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);
        $mpdf->WriteHTML($html);

        return $mpdf->Output('', 'S');
    }
}
