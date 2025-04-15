<?php

namespace App\Services\PdfGenerators;

class ProductsActivityReportPdfGenerator extends AbstractPdfGeneratorService
{
    public function generatePdf(array $data): string
    {
        $pdfContent = $this->generatePdfContent('pdf.productsActivityReport', ['reportData' => $data]);

        $filename = "products_activity_reports/products_report_" . now()->format('Y-m-d') . ".pdf";

        $pdfUrl = $this->save($pdfContent, $filename);

        return $pdfUrl;
    }
}
