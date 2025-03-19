<?php

namespace App\Services\PdfGenerators;

class SalesStockMonthlyReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.salesStockMonthlyReport', ['reportData' => $data]);

        $filename = "sales_stock_reports/sales_report_" .  now()->format('Y-m-d') . ".pdf";

        $pdfUrl = $this->save($pdfContent, $filename);

        return $pdfUrl;
    }
}
