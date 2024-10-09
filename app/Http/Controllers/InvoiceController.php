<?php

namespace App\Http\Controllers;

use App\Models\ClientOrder;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade\Pdf;
use Illuminate\Support\Facades\Storage;

class InvoiceController extends Controller
{
    public function generateInvoice($orderId)
    {
        $order = ClientOrder::with('products')->findOrFail($orderId);

        $data = [
            'order' => $order,
        ];

        $pdf = Pdf::loadView('invoices.client_invoice', $data);

        $clientId = $order->user_id;
        $path = public_path("clients_invoices/invoices_{$clientId}");

        if (!file_exists($path)) {
            mkdir($path, 0755, true);
        }

        $pdfPath = "{$path}/invoice_{$order->id}.pdf";
        $pdf->save($pdfPath);
        return response()->download($pdfPath, "invoice_{$order->id}.pdf", [
            'Content-Type' => 'application/pdf',
        ]);
    }

}
