<?php
namespace App\Services\PdfGenerators;

use Illuminate\Support\Facades\Log;

class GamesActivityMonthlyReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.gamesActivityMonthlyReport', ['reportData' => $data]);

        $filename = "games_activity_reports/games_activity_report_" .  now()->format('Y-m-d')  . ".pdf";

        $pdfUrl = $this->save($pdfContent, $filename);
        
        return $pdfUrl;
    }
}

