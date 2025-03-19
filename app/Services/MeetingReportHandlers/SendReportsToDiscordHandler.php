<?php
namespace App\Services\MeetingReportHandlers;

use App\Models\Meeting;
use App\Models\Report;
use App\Services\DiscordService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;
use Illuminate\Support\Facades\Storage;

class SendReportsToDiscordHandler extends AbstractMeetingReportHandler
{
    protected DiscordService $discordService;

    public function __construct(DiscordService $discordService)
    {
        $this->discordService = $discordService;
    }

    public function handle(?Meeting $meeting): void
    {
        if (!$meeting) {
            return;
        }

        $startTime = Carbon::parse($meeting->start)->format('H:i');
        $message = "🔥 **Hello!**\n\n📢 Despre astea vom discuta la meeting-ul ce începe în aprox *10 minute*!\n🕒 Ora de start: **{$startTime}**\n💼 Titlu: **{$meeting->title}**\n📄 Rapoarte atașate mai jos ⬇️";

        $this->discordService->sendMessage($message);

        $reports = Report::where('meeting_id', $meeting->id)->get();

        foreach ($reports as $report) {
            $s3Url = $report->s3_path;
            $s3DiskUrl = Storage::disk('s3')->url('/');
            $s3Path = str_replace($s3DiskUrl, '', $s3Url);

            $fileName = basename($s3Path);
            $localPath = storage_path("app/temp/{$fileName}");

            if (!file_exists(storage_path('app/temp'))) {
                mkdir(storage_path('app/temp'), 0777, true);
            }

            $fileContent = Storage::disk('s3')->get($s3Path);
            file_put_contents($localPath, $fileContent);

            $this->discordService->sendFile("📄 Raport: {$report->title}", "temp/{$fileName}");

            unlink($localPath);
        }
        parent::handle($meeting);
    }
}

