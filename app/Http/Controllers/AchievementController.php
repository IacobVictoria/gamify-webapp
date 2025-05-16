<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class AchievementController extends Controller
{
    public function index()
    {
        $user = Auth::user();

        if (!$user) { //unauthentificated user
            return;
        }

        $medals = $user->medals()->withPivot('created_at')->get();

        return Inertia::render('User/Achievements', [
            'medals' => $medals
        ]);
    }
}
