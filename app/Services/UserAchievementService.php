<?php

namespace App\Services;

use App\Enums\MedalTier;
use App\Interfaces\UserAchievementInterface;
use App\Jobs\SendMedalEmailJob;
use App\Mail\MedalEmail;
use App\Models\Medal;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Mail;

class UserAchievementService implements UserAchievementInterface
{

    public function checkAndSendMedalEmail($user, int $newScore, int $oldScore)
    {
        // dd();
        $newMedal = $this->getMedalByScore($newScore);
        $oldMedal = $this->getMedalByScore($oldScore);
       
        if ($newMedal !== $oldMedal) {
            $medal = Medal::firstOrCreate(['tier' => $newMedal]);
            
            $user->medals()->attach($medal->id);
            
            // Trimite email-ul
            SendMedalEmailJob::dispatch($user, $newMedal);
          
        }

    }
    private function getMedalByScore(int $score): ?string
    {
        if ($score >= 500) {
            return MedalTier::Gold->value;
        } elseif ($score >= 300) {
            return MedalTier::Silver->value;
        } elseif ($score >= 100) {
            return MedalTier::Bronze->value;
        }

        return null;
    }
}
