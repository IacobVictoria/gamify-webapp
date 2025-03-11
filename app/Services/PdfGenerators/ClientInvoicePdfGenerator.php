<?php
namespace App\Services\PdfGenerators;


class ClientInvoicePdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('invoices.client_invoice', ['order' => $data['order']]);
        $clientId = $data['order']->user_id;
        $path = "clients_invoices/{$clientId}/{$data['filename']}.pdf";
        return $this->save($pdfContent, $path);
    }
}
