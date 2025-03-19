<?php

namespace App\Jobs;

use App\Interfaces\MeetingReportHandlerInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ProcessMeetingReportsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected MeetingReportHandlerInterface $handler;

    public function __construct(MeetingReportHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    public function handle()
    {
        $this->handler->handle(null);
    }
}
