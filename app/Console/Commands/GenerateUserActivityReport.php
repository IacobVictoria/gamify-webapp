<?php

namespace App\Console\Commands;

use App\Factories\PdfGeneratorFactory;
use App\Factories\StorageStrategyFactory;
use App\Services\Reports\SalesStockReportService;
use App\Services\Reports\UserActivityReportService;
use Illuminate\Console\Command;

class GenerateUserActivityReport extends Command
{
    //testing pdf uri
    protected $signature = 'app:generate-user-activity-report';

    public function handle()
    {
        $reportService = new SalesStockReportService();
        $reportData = $reportService->getMonthlyReport();
        $storageStrategy = StorageStrategyFactory::create('s3');

        $pdfGenerator = PdfGeneratorFactory::create('sales_stock_monthly', $storageStrategy);
        $pdfUrl = $pdfGenerator->generatePdf($reportData);

        if ($pdfUrl) {
            $this->info("✅ Raport generat cu succes: {$pdfUrl}");
        } else {
            $this->error("❌ Eroare la generarea PDF-ului.");
        }
    }
}
