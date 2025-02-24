<?php

namespace App\Http\Controllers;

use App\Enums\BadgeCategory;
use App\Models\Badge;
use App\Models\Medal;
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
        // Obține toate badge-urile disponibile
        $allBadges = Badge::all();

        // Obține badge-urile deținute de utilizator
        $userBadges = $user->badges()->get()->pluck('id')->toArray();

        // Marchează badge-urile deținute
        $badgesWithOwnership = $allBadges->map(function ($badge) use ($userBadges) {
            // Adaugă un atribut pentru a marca dacă utilizatorul deține badge-ul
            $badge->owned = in_array($badge->id, $userBadges);
            return $badge;
        });

        // Obține toate medaliile deținute de utilizator
        $userMedals = $user->medals()->get()->pluck('id')->toArray();

        // Obține toate medaliile disponibile
        $allMedals = Medal::all();

        // Marchează medaliile deținute
        $medalsWithOwnership = $allMedals->map(function ($medal) use ($userMedals) {
            $medal->owned = in_array($medal->id, $userMedals);
            return $medal;
        });


        $top10Players = User::orderBy('score', 'desc')
            ->limit(10)
            ->get();

        $userScore = $user->score;

        $yourPositionInTop = null;
        if ($userScore) {
            $yourPositionInTop = User::where('score', '>', $userScore)
                ->count() + 1;
        }

        $categories = BadgeCategory::cases();

        $categories = array_map(function ($category) {
            return [
                'value' => $category->value,
                'label' => ucfirst(str_replace('_', ' ', $category->value)) // Convertim enum-ul în format prietenos (ex. 'reviewer' => 'Reviewer')
            ];
        }, $categories);

        return Inertia::render('User/UserDashboard/GameCenter/Index', [
            'badges' => $badgesWithOwnership,
            'medals' => $medalsWithOwnership,
            'top10Players' => $top10Players,
            'yourPositionInTop' => $yourPositionInTop,
            'categories' => $categories
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function badges_index()
    {
        $allBadges = Badge::all();
        return Inertia::render('User/UserDashboard/GameCenter/BadgeList', [
            'badges' => $allBadges
        ]);
    }
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
