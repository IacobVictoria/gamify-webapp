<?php
namespace App\Services\MeetingReportHandlers;

use App\Models\Meeting;

class CloseMeetingHandler extends AbstractMeetingReportHandler
{
    public function handle(?Meeting $meeting): void
    {
        if (!$meeting) {
            return;
        }

        $meeting->update(['status' => 'CLOSED']);

        parent::handle($meeting);
    }
}
