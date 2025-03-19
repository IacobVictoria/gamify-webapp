<?php

namespace App\Interfaces;

use App\Models\Meeting;

interface MeetingReportHandlerInterface
{
    public function setNext(MeetingReportHandlerInterface $handler): MeetingReportHandlerInterface;

    public function handle(?Meeting $meeting): void;
}