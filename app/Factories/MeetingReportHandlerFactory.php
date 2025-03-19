<?php
namespace App\Factories;

use App\Services\MeetingReportHandlers\FetchMeetingsHandler;
use App\Services\MeetingReportHandlers\CloseMeetingHandler;
use App\Services\MeetingReportHandlers\GenerateReportsHandler;
use App\Services\MeetingReportHandlers\SendReportsToDiscordHandler;
use App\Services\Reports\ProductsActivityReportService;
use App\Services\DiscordService;

class MeetingReportHandlerFactory
{
    public static function create($app)
    {
        $fetchMeetingsHandler = new FetchMeetingsHandler();
        $closeMeetingHandler = new CloseMeetingHandler();
        $generateReportsHandler = new GenerateReportsHandler();
        $sendToDiscordHandler = new SendReportsToDiscordHandler($app->make(DiscordService::class));

        $fetchMeetingsHandler->setNext($closeMeetingHandler)
            ->setNext($generateReportsHandler)
            ->setNext($sendToDiscordHandler);

        return $fetchMeetingsHandler;
    }
}
