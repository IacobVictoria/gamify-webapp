<?php

namespace App\Services\PdfGenerators;

class ProductsActivityMonthlyReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.productsActivityMonthlyReport', ['reportData' => $data]);

        $filename = "products_activity_reports/products_report_" . now()->format('Y-m-d') . ".pdf";

        $pdfUrl = $this->save($pdfContent, $filename);

        return $pdfUrl;
    }
}
