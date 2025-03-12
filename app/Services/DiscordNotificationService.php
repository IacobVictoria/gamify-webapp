<?php

namespace App\Services;

use App\Models\Survey;
use Illuminate\Support\Facades\Storage;
use App\Services\NpsService;
use Carbon\Carbon;

class DiscordNotificationService
{
    protected $npsService;
    protected $discordService;

    public function __construct(NpsService $npsService, DiscordService $discordService)
    {
        $this->npsService = $npsService;
        $this->discordService = $discordService;
    }

    public function sendNpsReportToDiscord()
    {
        $lastMonth = Carbon::now()->format('Y-m');
        $survey = Survey::where('is_published', true)->first();

        if (!$survey) {
            return;
        }

        // Generează PDF-ul pentru luna trecută
        $pdfPath = $this->npsService->generateNpsReportPdf($survey->id, $lastMonth);

        // Extragem numele fișierului din calea S3
        $fileName = basename($pdfPath);
        $localPath = storage_path("app/temp/{$fileName}"); // Salvăm PDF-ul temporar

        // Creăm folderul temp dacă nu există
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        // Descărcăm PDF-ul din S3 și îl salvăm local
        $fileContent = Storage::disk('s3')->get(str_replace(Storage::disk('s3')->url('/'), '', $pdfPath));
        file_put_contents($localPath, $fileContent);

        // Trimitem PDF-ul prin DiscordService
        $this->discordService->sendFile("📊 Monthly NPS Report for *{$lastMonth}* is ready! 📩", "temp/{$fileName}");

        // Ștergem fișierul temporar
        unlink($localPath);
    }
}
