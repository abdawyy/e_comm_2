<?php

namespace App\Http\Controllers;

use App\Models\Order;
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

        $order->loadMissing('shippingCarrier');
        $locale = in_array($order->locale, ['en', 'ar'], true) ? $order->locale : 'ar';
        $directory = storage_path('app/mpdf');

        if (! is_dir($directory)) {
            mkdir($directory, 0755, true);
        }

        $pdf = new Mpdf([
            'mode' => 'utf-8',
            'format' => 'A6',
            'default_font' => 'dejavusans',
            'tempDir' => $directory,
            'autoScriptToLang' => true,
            'autoLangToFont' => true,
        ]);
        $pdf->WriteHTML(view('pdf.shipping-label', [
            'order' => $order,
            'locale' => $locale,
            'math' => $math,
        ])->render());

        $filename = preg_replace('/[^A-Za-z0-9._-]/', '', (string) $order->reference) ?: 'label';

        return response($pdf->Output('', 'S'), 200, [
            'Content-Type' => 'application/pdf',
            'Content-Disposition' => 'inline; filename="'.$filename.'-label.pdf"',
        ]);
    }
}
