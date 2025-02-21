<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserGameCenterController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $badges = $user->badges()->get();

        $medals = $user->medals()->get();

        $top10Players = User::orderBy('score', 'desc')
            ->limit(10)
            ->get();

        $userScore = $user->score;

        $yourPositionInTop = null;
        if ($userScore) {
            $yourPositionInTop = User::where('score', '>', $userScore)
                ->count() + 1;
        }

        return Inertia::render('User/UserDashboard/GameCenter/Index', [
            'badges' => $badges,
            'medals' => $medals,
            'top10Players' => $top10Players,
            'yourPositionInTop' => $yourPositionInTop,
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
