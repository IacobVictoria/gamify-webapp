<?php

namespace App\Services\PdfGenerators;

class RewardsActivityMonthlyReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.rewardsActivityMonthlyReport', ['reportData' => $data]);

        $filename = "rewards_activity_reports/" . now()->format('Y-m') . ".pdf";

        $pdfUrl = $this->save($pdfContent, $filename);

        return $pdfUrl;
    }
}
