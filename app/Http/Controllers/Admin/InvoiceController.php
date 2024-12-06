<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;

use App\Models\Order;
use App\Models\RentOrder;
use Barryvdh\DomPDF\Facade\Pdf;

class InvoiceController extends Controller
{
    
    public function invoice_download($id)
    {
        $direction = 'ltr';
        $text_align = 'left';
        $not_text_align = 'right';
        
        //$font_family = "'Roboto','sans-serif'";
        $font_family = "'Roboto','sans-serif'";
        $order = Order::findOrFail($id);

        set_time_limit(300);

       

        $pdf = Pdf::loadView('backend.invoices.invoice', [
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align,
            'imagePath' => asset('/admin_assets/assets/img/logo.png')
        ]);
        
        return $pdf->download('order-' . $order->code . '.pdf');
    }
    public function rentInvoice_download($id)
    {
        $direction = 'ltr';
        $text_align = 'left';
        $not_text_align = 'right';
        
        //$font_family = "'Roboto','sans-serif'";
        $font_family = "'Roboto','sans-serif'";
        $order = RentOrder::findOrFail($id);

        set_time_limit(300);

       

        $pdf = Pdf::loadView('backend.invoices.rentinvoice', [
            'order' => $order,
            'font_family' => $font_family,
            'direction' => $direction,
            'text_align' => $text_align,
            'not_text_align' => $not_text_align,
            'imagePath' => asset('/admin_assets/assets/img/logo.png')
        ]);
        return $pdf->download('rent-order-' . $order->order_code . '.pdf');
    }
}
