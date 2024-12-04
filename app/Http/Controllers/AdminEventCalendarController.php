<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminEventCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::all();
        return Inertia::render('Admin/Calendar/Index', [
            'events' => $events,
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

        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'start' => 'required|string', 
            'end' => 'required|string',   
            'status' => 'nullable|in:CLOSED,OPEN',
            'type' => 'nullable|string',
            'details' => 'nullable|json',
            'calendarId' => 'string',
        ]);

        $event = new Event([
            'id' => Uuid::uuid(),
            'title' => $validated['title'],
            'description' => $validated['description'],
            'start' => $validated['start'], 
            'end' => $validated['end'],
            'status' => $validated['status'] ?? 'OPEN', 
            'type' => $validated['type'] ?? 'event',
            'details' => $validated['details'] ?? null,
            'calendarId' => $validated['calendarId'],
        ]);

        $event->save();

        return redirect()->back()->with('success', 'Event created successfully!');

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
        $event = Event::findOrFail($id);
        $validatedData = $request->validate([
            'payload.title' => 'required|string|max:255',
            'payload.description' => 'required|string|max:500',
            'payload.start' => 'required|date',
            'payload.end' => 'required|date|after:payload.start',
            'payload.status' => 'required|in:CLOSED,OPEN',  
        ]);
        $payload = $request->input('payload');

        $event->title = $payload['title'];
        $event->description = $payload['description'];
        $event->start = $payload['start'];
        $event->end = $payload['end'];
        $event->status = $payload['status'];

        $event->save();

        return response()->json(['message' => 'Event updated successfully']);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
