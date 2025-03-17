<?php
namespace App\Services\PdfGenerators;

use Illuminate\Support\Facades\Log;

class GamesActivityMonthlyReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.gamesActivityMonthlyReport', ['reportData' => $data]);

        $filename = "games_activity_reports/" . now()->format('Y-m') . ".pdf";

        $pdfUrl = $this->save($pdfContent, $filename);
        
        return $pdfUrl;
    }
}

