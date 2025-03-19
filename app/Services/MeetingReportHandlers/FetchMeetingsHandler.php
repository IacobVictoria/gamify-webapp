<?php
namespace App\Services\MeetingReportHandlers;

use App\Models\Meeting;
use Carbon\Carbon;

class FetchMeetingsHandler extends AbstractMeetingReportHandler
{
    public function handle(?Meeting $meeting = null): void
    {
        $now = Carbon::now();

        $tenMinutesLater = $now->copy()->addMinutes(10);

        // Găsim meeting-urile care încep în ≤ 10 minute
        $meetings = Meeting::where('status', 'OPEN')
            ->where('start', '<=', $tenMinutesLater) 
            ->where('start', '>=', $now) 
            ->get();

        if ($meetings->isEmpty()) {
            return;
        }

        // Procesam fiecare meeting separat prin chain-ul de handleri
        foreach ($meetings as $meeting) {
            parent::handle($meeting);
        }
    }
}
