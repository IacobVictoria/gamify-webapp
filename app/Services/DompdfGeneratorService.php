<?php

namespace App\Services;

use App\Interfaces\PdfGeneratorServiceInterface;
use Dompdf\Dompdf;
use Dompdf\Options;
use Illuminate\Support\Facades\Storage;
class DompdfGeneratorService implements PdfGeneratorServiceInterface
{
    /**
     * Generează un PDF cu lista participanților și îl salvează în S3.
     *
     * @param array $participants Lista participanților.
     * @param string $filename Numele fișierului generat.
     * @return string URL-ul către fișierul PDF salvat.
     */
    public function generateParticipantsListPdf(array $event, array $participants, string $filename, int $confirmedCount, int $notConfirmedCount, float $confirmationPercentage, int $totalParticipants): string
    {
        // Configurăm Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Generăm HTML-ul pentru PDF
        $html = view('pdf.participants', compact('event', 'participants', 'confirmedCount', 'notConfirmedCount', 'confirmationPercentage', 'totalParticipants'))->render();

        // Încărcăm HTML-ul și generăm PDF-ul
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Obținem conținutul PDF-ului
        $pdfContent = $dompdf->output();

        // Salvează PDF-ul în S3
        $path = "pdf_reports/{$filename}";
        Storage::disk('s3')->put($path, $pdfContent, 'public');

        // Returnăm URL-ul fișierului din S3
        return Storage::disk('s3')->url($path);
    }
    public function generateInvoicePdf(array $invoiceData, string $filename): string
    {   // Configurăm Dompdf
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        // Generăm HTML-ul pentru PDF
        $html = view('pdf.invoiceSupplier', compact('invoiceData'))->render();

        // Încărcăm HTML-ul și generăm PDF-ul
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        // Obținem conținutul PDF-ului
        $pdfContent = $dompdf->output();

        // Salvează PDF-ul în S3
        $path = "supplier_invoices/{$filename}";
        Storage::disk('s3')->put($path, $pdfContent, 'public');

        // Returnăm URL-ul fișierului din S3
        return Storage::disk('s3')->url($path);
    }

}