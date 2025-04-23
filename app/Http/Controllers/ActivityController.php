<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class ActivityController extends Controller
{
    public function index()
    {
        $activities = Activity::where('is_published', true)
            ->orderBy('created_at', 'desc')
            ->get();

        return Inertia::render('Activities/Index', [
            'activities' => $activities
        ]);
    }

    public function show($slug)
    {
        $activity = Activity::where('slug', $slug)->firstOrFail();

        $isParticipant = false;
        $isFavorited = false;

        if (Auth::check()) {
            $participant = $activity->participants()
                ->where('user_id', Auth::id())
                ->first();

            $isParticipant = (bool) $participant;
            $isFavorited = $participant?->is_favorite ?? false;
        }

        $user = Auth()->user();
        $friends = $user->allFriends()->map(function ($friend) {
            return [
                'name' => $friend->name,
                'id' => $friend->id,
            ];
        });

        return Inertia::render('Activities/Show', [
            'activity' => $activity,
            'alreadyParticipating' => $isParticipant,
            'isFavorited' => $isFavorited,
            'friends' => $friends
        ]);
    }

}
