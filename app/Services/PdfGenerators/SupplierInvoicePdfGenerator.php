<?php
namespace App\Services\PdfGenerators;

use Illuminate\Support\Facades\Log;

class SupplierInvoicePdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.invoiceSupplier', ['invoiceData' => $data]);

        $path = "supplier_invoices/{$data['filename']}.pdf";

        $pdfUrl = $this->save($pdfContent, $path);
        
        return $pdfUrl;
    }
}

