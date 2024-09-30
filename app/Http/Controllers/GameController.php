<?php

namespace App\Http\Controllers;

use App\Interfaces\UserAchievementInterface;
use App\Services\UserAchievementService;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class GameController extends Controller
{
    private $achievementInterface;

    public function __construct(UserAchievementInterface $achievementInterface)
    {
        $this->achievementInterface = $achievementInterface;
    }

    public function index()
    {
        return Inertia::render('Admin/QrCodes');
    }

    public function updateScore(Request $request)
    {
        $user = Auth::user();

        if (!$user) { //unauthentificated user
            return;
        }
        $oldScore = $user->score;
        $newScore = $user->score + $request->input('score');
        $this->achievementInterface->checkAndSendMedalEmail($user, $newScore, $oldScore);
        // dd();
        // dd($newScore, $oldScore);
        $user->update(['score' => $newScore]);

        // Check if the user has earned a medal 
    }


}
