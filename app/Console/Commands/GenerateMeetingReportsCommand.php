<?php

namespace App\Console\Commands;

use App\Interfaces\MeetingReportHandlerInterface;
use App\Jobs\ProcessMeetingReportsJob;
use Illuminate\Console\Command;

class GenerateMeetingReportsCommand extends Command
{
    protected $signature = 'meetings:process-reports';
    protected $description = 'Generează rapoartele întâlnirilor care urmează să înceapă.';

    protected MeetingReportHandlerInterface $handler;

    public function __construct(MeetingReportHandlerInterface $handler)
    {
        parent::__construct();
        $this->handler = $handler;
    }

    public function handle()
    {
        dispatch(new ProcessMeetingReportsJob($this->handler));
        $this->info('Job-ul pentru generarea rapoartelor întâlnirilor a fost lansat.');
    }
}
