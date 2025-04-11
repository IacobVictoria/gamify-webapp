<?php
namespace App\Services\User;

use App\Models\User;

abstract class AbstractScoreHandler implements ScoreHandlerInterface
{
    protected ?ScoreHandlerInterface $next = null;

    public function setNext(ScoreHandlerInterface $handler): ScoreHandlerInterface
    {
        $this->next = $handler;
        return $handler;
    }

    protected function next(User $user, int $score): void
    {
        if ($this->next) {
            $this->next->handle($user, $score);
        }
    }
}
