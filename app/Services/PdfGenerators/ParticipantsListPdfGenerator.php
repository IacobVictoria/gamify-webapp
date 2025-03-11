<?php
namespace App\Services\PdfGenerators;
class ParticipantsListPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        // Generăm conținutul PDF folosind view-ul corect
        $pdfContent = $this->generatePdfContent('pdf.participants', $data);

        // Setăm calea unde salvăm PDF-ul
        $path = "pdf_reports/{$data['filename']}.pdf";

        // Salvăm PDF-ul folosind strategia de stocare
        return $this->save($pdfContent, $path);
    }
}