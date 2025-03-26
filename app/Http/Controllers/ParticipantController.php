<?php

namespace App\Http\Controllers;

use App\Models\Activity;
use App\Models\Event;
use App\Models\Participant;
use App\Services\UserScoreService;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class ParticipantController extends Controller
{
    protected $userScoreService;
    public function __construct(UserScoreService $userScoreService)
    {
        $this->userScoreService = $userScoreService;
    }
    public function store(Request $request, $activityId)
    {
        $activity = Activity::findOrFail($activityId);

        // Verifică dacă user-ul a participat deja
        $existing = Participant::where('user_id', Auth::id())
            ->where('activity_id', $activity->id)
            ->first();

        if ($existing) {
            return redirect()->back()->with('message', 'You already completed this activity!');
        }

        // Creează participantul
        $participant = new Participant();
        $participant->id = Uuid::uuid();
        $participant->activity_id = $activity->id;
        $participant->user_id = Auth::id();
        $participant->save();

        // Acordă scor
        $this->userScoreService->addScore($participant->user, $activity->score ?? 10);

        return redirect()->back()->with('message', 'Activity completed!');
    }

    public function toggleFavorite(Request $request, $activityId)
    {
        $participant = Participant::where('user_id', Auth::id())
            ->where('activity_id', $activityId)
            ->first();

        if (!$participant) {
            return redirect()->back()->with('error', 'You need to complete the activity before adding it to favorites.');
        }

        $participant->is_favorite = !$participant->is_favorite;
        $participant->save();

        $message = $participant->is_favorite
            ? '✅ Activity added to favorites.'
            : '❌ Activity removed from favorites.';

        return redirect()->back()->with('message', $message);
    }



}
