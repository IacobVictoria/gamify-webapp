<?php

namespace App\Interfaces;

use App\Models\User;

interface BadgeAssignerInterface
{
    public function assignBadge(User $user, string $badgeName): void;
}
//gestionează procesul efectiv de atribuire a badge-urilor
//Nu decide dacă utilizatorul merită badge-ul, doar îl adaugă în baza de date.