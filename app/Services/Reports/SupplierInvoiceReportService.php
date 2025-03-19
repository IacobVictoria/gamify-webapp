<?php

namespace App\Services\Reports;

use App\Models\Report;
use App\Models\ReportCategory;
use Faker\Provider\Uuid;

class SupplierInvoiceReportService
{
    public function createSupplierInvoiceReport(string $title, string $filePath): Report
    {
        return Report::create([
            'id' => Uuid::uuid(),
            'report_category_id' => $this->getReportCategoryId('supplier_invoice'),
            'title' => $title,
            's3_path' => $filePath,
            'meeting_id' => null,
        ]);
    }

    private function getReportCategoryId(string $type): string
    {
        return ReportCategory::where('name', $type)->firstOrFail()->id;
    }
}