<?php

namespace App\Observers;

use App\Models\User;
use App\Services\UserAchievementService;

class UserObserver
{
    protected $achievementService;

    public function __construct(UserAchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        //
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
          // Check if the score has been updated
          if ($user->isDirty('score')) {
            $newScore = $user->score;
            $this->achievementService->checkAndSendMedalEmail($user, $newScore,$user->score);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        //
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
    {
        //
    }
}
