<?php

namespace App\Services\PdfGenerators;

class RewardsActivityReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.rewardsActivityReport', ['reportData' => $data]);

        $filename = "rewards_activity_reports/rewards_report_" .  now()->format('Y-m-d')  . ".pdf";

        $pdfUrl = $this->save($pdfContent, $filename);

        return $pdfUrl;
    }
}
