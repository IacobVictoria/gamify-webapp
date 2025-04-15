<?php

namespace App\Services\PdfGenerators;

class UserActivityReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.userActivityReport', ['reportData' => $data]);

        $filename = "user_activity_reports/user_activity_report_" .  now()->format('Y-m-d')  . ".pdf";

        $pdfUrl = $this->save($pdfContent, $filename);

        return $pdfUrl;
    }
}
