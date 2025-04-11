<?php
namespace App\Services\User;

use App\Events\UserScoreUpdatedEvent;
use App\Models\User;
use App\Services\NotificationService;

class BroadcastScoreHandler extends AbstractScoreHandler
{
    protected NotificationService $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        $this->notificationService = $notificationService;
    }

    public function handle(User $user, int $score): void
    {
        broadcast(new UserScoreUpdatedEvent(
            $user,
            $score,
            "Ai primit $score !",
            $this->notificationService
        ));

        $this->next($user, $score);
    }
}
