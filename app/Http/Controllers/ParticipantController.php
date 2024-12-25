<?php

namespace App\Http\Controllers;

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
    public function store(Request $request, $eventId)
    {
        $event = Event::findOrFail($eventId);

        $participant = new Participant();
        $participant->id = Uuid::uuid();
        $participant->event_id = $event->id;
        $participant->user_id = Auth::id();
        $participant->confirmed = false;
        $participant->save();
        
        $this->userScoreService->addScore($participant->user, 10);

        return redirect()->back();

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
