<?php
namespace App\Services\MeetingReportHandlers;

use App\Interfaces\MeetingReportHandlerInterface;
use App\Models\Meeting;

abstract class AbstractMeetingReportHandler implements MeetingReportHandlerInterface
{
    protected ?MeetingReportHandlerInterface $nextHandler = null;

    public function setNext(MeetingReportHandlerInterface $next): MeetingReportHandlerInterface
    {
        $this->nextHandler = $next;
        return $next;
    }

    public function handle(?Meeting $meeting): void
    {
        if ($this->nextHandler) {
            $this->nextHandler->handle($meeting);
        }
    }
}
