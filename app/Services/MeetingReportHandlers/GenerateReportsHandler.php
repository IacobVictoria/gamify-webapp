<?php
namespace App\Services\MeetingReportHandlers;

use App\Factories\PdfGeneratorFactory;
use App\Factories\ReportServiceFactory;
use App\Factories\StorageStrategyFactory;
use App\Models\Meeting;
use App\Models\Report;
use App\Models\ReportCategory;
use Carbon\Carbon;
use Faker\Provider\Uuid;
use Illuminate\Support\Facades\Log;

class GenerateReportsHandler extends AbstractMeetingReportHandler
{
    public function handle(?Meeting $meeting): void
    {
        $meetingDate = Carbon::parse($meeting->start);
        $period = $meeting->period;

        foreach ($meeting->report_category_ids as $categoryId) {
                $category = ReportCategory::findOrFail($categoryId);
     
            $reportService = ReportServiceFactory::create($category->name);
         
            $storageStrategy = StorageStrategyFactory::create('s3');

            $reportData = $reportService->getReportByPeriod($period, $meetingDate);

            $pdfGenerator = PdfGeneratorFactory::create($category->name, $storageStrategy);
          
            $pdfUrl = $pdfGenerator->generatePdf($reportData);
       
                Report::create([
                    'id' => Uuid::uuid(),
                    'meeting_id' => $meeting->id,
                    'report_category_id' => $categoryId,
                    'title' => "Raport {$category->name} pentru meeting-ul {$meeting->id}",
                    's3_path' => $pdfUrl,
                ]);
        }

        parent::handle($meeting);
    }
}
