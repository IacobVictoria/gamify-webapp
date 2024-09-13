<?php

namespace App\Services;

use App\Enums\MedalTier;
use App\Interfaces\UserAchievementInterface;
use App\Mail\MedalEmail;
use App\Models\Message;
use App\Models\User;
use Illuminate\Contracts\Auth\Authenticatable;
use Illuminate\Support\Facades\Mail;

class UserAchievementService implements UserAchievementInterface
{

    public function checkAndSendMedalEmail(User $user, int $newScore, int $oldScore)
    {
        $newMedal = $this->getMedalByScore($newScore);
        $oldMedal = $this->getMedalByScore($oldScore);
       // dd( $newMedal, $oldMedal);
        if ($newMedal !== $oldMedal ) {
        

            // Create a new message instance for the email
            $message = new Message([
                'name' => $user->name,
                'email' => $user->email,
                'birthdate' => $user->birthdate,
                'medal' => $newMedal,
                'message' => 'Congratulations on your achievement!',
            ]);

            // Send the email
            Mail::to($user->email)->send(new MedalEmail($message));
      
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
