<?php

namespace App\Services;

use App\Events\UserMedalAwardedEvent;
use App\Jobs\SendMedalAwardedMailJob;
use App\Models\Medal;
use App\Models\User;

class MedalService
{
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }
    public function checkAndAwardMedal(User $user)
    {
        $score = $user->score;
        $existingMedals = $user->medals->pluck('tier')->toArray();

        // Lista medaliilor pe care le poate primi
        $medals = [
            'bronze' => 50,
            'silver' => 100,
            'gold' => 300
        ];
        //să primească toate medaliile pentru care devine eligibil, chiar dacă sare peste anumite praguri

        foreach ($medals as $tier => $threshold) {
            if ($score >= $threshold && !in_array($tier, $existingMedals)) {
                $this->awardMedal($user, $tier);
            }
        }
    }
    public function awardMedal(User $user, string $tier)
    {
        $medal = Medal::where('tier', $tier)->first();
        if ($medal) {
            $user->medals()->attach($medal->id);

            // Trimitere email prin job
            SendMedalAwardedMailJob::dispatch($user, $tier);
        }
        broadcast(new UserMedalAwardedEvent($user, $medal, $this->notificationService));
    }

}
