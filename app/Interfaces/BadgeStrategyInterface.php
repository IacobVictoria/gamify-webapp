<?php

namespace App\Interfaces;

use App\Models\User;

//appliesTo(User $user): Verifică dacă utilizatorul îndeplinește criteriile badge-ului.
//getBadgeName(): Returnează numele badge-ului.
interface BadgeStrategyInterface
{
    public function appliesTo(User $user): bool;
    public function getBadgeName(): string;
}