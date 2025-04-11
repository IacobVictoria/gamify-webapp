<?php
namespace App\Services\User;

use App\Models\User;

class SaveScoreHandler extends AbstractScoreHandler
{
    public function handle(User $user, int $score): void
    {
        $user->score += $score;
        $user->save();

        $this->next($user, $score);
    }
}
