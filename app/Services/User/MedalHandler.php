<?php
namespace App\Services\User;

use App\Models\User;
use App\Services\MedalService;

class MedalHandler extends AbstractScoreHandler
{
    protected MedalService $medalService;

    public function __construct(MedalService $medalService)
    {
        $this->medalService = $medalService;
    }

    public function handle(User $user, int $score): void
    {
         $this->medalService->checkAndAwardMedal($user);

        $this->next($user, $score);
    }
}
