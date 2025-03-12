<?php
namespace App\Services\PdfGenerators;


class NpsReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.nps_month_statistics', $data);
        $path = "nps_reports/{$data['surveyId']}_{$data['surveyPeriod']}.pdf";
        return $this->save($pdfContent, $path);
    }
}
