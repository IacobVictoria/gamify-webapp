<?php

namespace App\Services\DashboardRolesType;

use App\Models\User;
use Illuminate\Support\Facades\Auth;

class UserDashboardService
{
    public function getDashboardData()
    {
        $user = Auth::user();
        $userScore = $user->score;
        $yourPositionInTop = User::where('score', '>', $userScore)->count() + 1;

        return [
            'account' => [
                'name' => $user->name,
                'score' => $userScore,
                'nr_badges' => $user->badges()->count(),
                'position_leaderboard' => $yourPositionInTop,
                'gender' => $user->gender
            ]
        ];
    }
}