<?php

namespace App\Services\Badges;

use App\Events\ObtainBadge;
use App\Interfaces\BadgeAssignerInterface;
use App\Interfaces\UserScoreInterface;
use App\Models\Badge;
use App\Models\User;
use App\Services\NotificationService;
use Faker\Provider\Uuid;
/**
 * Clasa BadgeAssignerService este responsabilă doar de atribuirea badge-urilor utilizatorilor.
 * Respectă SRP deoarece nu conține logica de validare a eligibilității badge-urilor.
 */
class BadgeAssignerService implements BadgeAssignerInterface
{
    public $userScoreService, $notificationService;

    /**
     * Respectă DIP (Dependency Inversion Principle) prin utilizarea interfețelor.
     */
    public function __construct(UserScoreInterface $service, NotificationService $notificationService)
    {
        $this->userScoreService = $service;
        $this->notificationService = $notificationService;
    }

    public function assignBadge(User $user, string $badgeName): void
    {
        if ($user->badges()->where('name', $badgeName)->exists()) {
            return;
        }

        $badge = Badge::where('name', $badgeName)->firstOrFail();
        $user->badges()->attach($badge, ['id' => Uuid::uuid(), 'awarded_at' => now()]);

        $this->userScoreService->addScore($user, $badge->score);

        event(new ObtainBadge($user, $badge, $this->notificationService));
    }

}