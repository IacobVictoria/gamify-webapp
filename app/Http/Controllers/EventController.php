<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Participant;
use App\Models\QrCodeEvent;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class EventController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
{
    // Obține reducerile active (discounts)
    $activeDiscounts = Event::where('type', 'discount')
        ->where('status', 'OPEN')
        ->where('start', '<=', now())  // Reduceri care au început deja
        ->where('end', '>=', now())
        ->where('is_published', true)  // Reduceri care nu au expirat
        ->get();

    // Obține evenimentele viitoare (event)
    $activeEvents = Event::where('type', 'event')
        ->where('status', 'OPEN')
        ->where('start', '>', now())
        ->where('is_published', true)  // Evenimente care vor începe în viitor
        ->get();

    foreach ($activeDiscounts as $event) {
        if ($event->type === 'discount' && $event->details) {
            $event->details = json_decode($event->details, true);
        }
    }
 

    return Inertia::render('Events/Index', [
        'activeDiscounts' => $activeDiscounts,
        'activeEvents' => $activeEvents
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
    public function show($id)
    {
        $event = Event::findOrFail($id);

        $qrCode = QrCodeEvent::where('event_id', $event->id)->first();
        $isParticipant = Participant::where('event_id', $event->id)
        ->where('user_id', Auth::id())
        ->exists();


        return Inertia::render('Events/Show', [
            'event' => $event,
            'qrCode'=>$qrCode->image_url,
            'isParticipant'=>$isParticipant,
        ]);
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
