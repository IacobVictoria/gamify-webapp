<?php
namespace App\Services\PdfGenerators;


class NpsReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.npsStatistics', ['reportData' => $data]);
        $path = "nps_reports/nps_report_" .  now()->format('Y-m-d')  .".pdf";
        return $this->save($pdfContent, $path);
    }
}
