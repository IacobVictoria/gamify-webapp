<?php

namespace App\Services\PdfGenerators;

class SalesStockMonthlyReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.salesStockMonthlyReport', ['reportData' => $data]);

        $filename = "sales_stock_reports/" . now()->format('Y-m') . ".pdf";

        $pdfUrl = $this->save($pdfContent, $filename);

        return $pdfUrl;
    }
}
