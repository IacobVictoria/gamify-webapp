<?php

namespace App\Services\PdfGenerators;

class UserActivityMonthlyReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.userActivityMonthlyReport', ['reportData' => $data]);

        $filename = "user_activity_reports/user_activity_report_" .  now()->format('Y-m-d')  . ".pdf";

        $pdfUrl = $this->save($pdfContent, $filename);

        return $pdfUrl;
    }
}
