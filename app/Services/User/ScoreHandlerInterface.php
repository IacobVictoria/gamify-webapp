<?php
namespace App\Services\User;

use App\Models\User;

interface ScoreHandlerInterface
{
    public function setNext(ScoreHandlerInterface $handler): ScoreHandlerInterface;
    public function handle(User $user, int $score): void;
}


