<?php

namespace App\Http\Controllers;

use App\Models\DesignAsset;
use App\Models\Order;
use App\Models\SiteTheme;
use App\Services\DecimalMath;
use Illuminate\Http\Request;
use Illuminate\Http\Response;
use Mpdf\Mpdf;

class ShippingLabelController extends Controller
{
    public function __invoke(Request $request, Order $order, DecimalMath $math): Response
    {
        abort_unless($request->user()?->can('view', $order), 403);
        abort_unless($order->shipping_carrier_id, 404);

        $order->loadMissing(['items', 'shippingCarrier']);
        $locale = in_array($order->locale, ['en', 'ar'], true) ? $order->locale : 'ar';
        $previous = app()->getLocale();
        app()->setLocale($locale);
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
            'margin_left' => 10,
            'margin_right' => 10,
            'margin_top' => 10,
            'margin_bottom' => 10,
        ]);
        $pdf->WriteHTML(view('pdf.invoice', [
            'order' => $order,
            'theme' => SiteTheme::current(),
            'logo' => DesignAsset::urlFor('logo'),
            'math' => $math,
            'locale' => $locale,
            'forDelivery' => true,
        ])->render());
        app()->setLocale($previous);

        $filename = preg_replace('/[^A-Za-z0-9._-]/', '', (string) $order->reference) ?: 'label';

        return response($pdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'-label.pdf"',
        ]);
    }
}
