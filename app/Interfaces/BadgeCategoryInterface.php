<?php

namespace App\Interfaces;

use App\Models\User;

interface BadgeCategoryInterface
{
    public function checkAndAssignBadges(User $user): void;
}
//O metodă care verifică și atribuie badge-urile în funcție de criteriile unei categorii
//Se ocupă doar de verificarea și decizia dacă un utilizator trebuie să primească un badge
