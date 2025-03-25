<?php

namespace App\Services;

use App\Events\UserMedalAwardedEvent;
use App\Jobs\SendMedalAwardedMailJob;
use App\Models\Medal;
use App\Models\User;

class MedalService
{
    protected $notificationService, $discountService;

    public function __construct(NotificationService $notificationService, DiscountService $discountService)
    {
        $this->notificationService = $notificationService;
        $this->discountService = $discountService;
    }
    public function checkAndAwardMedal(User $user)
    {
        $score = $user->score;
        $existingMedals = $user->medals->pluck('tier')->toArray();

        // Obținem toate medaliile ordonate crescător după prag (threshold)
        $medals = Medal::orderBy('threshold')->get();

        //să primească toate medaliile pentru care devine eligibil, chiar dacă sare peste anumite praguri

        foreach ($medals as $medal) {
            if ($score >= $medal->threshold && !in_array($medal->tier, $existingMedals)) {
                $this->awardMedal($user, $medal->tier);
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

            $this->discountService->assignPromoForMedalAndNotify($user, $tier);
        }
        broadcast(new UserMedalAwardedEvent($user, $medal, $this->notificationService));
    }

}
