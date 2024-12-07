<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Product;
use App\Models\Supplier;
use App\Models\SupplierProduct;
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
        $categories = Product::distinct()->pluck('category');
        $suppliers = Supplier::all();
        $productsBySupplier = SupplierProduct::with('supplier')
            ->get()
            ->groupBy('supplier_id');
         
        return Inertia::render('Admin/Calendar/Index', [
            'events' => $events,
            'categories' => $categories,
            'suppliers' => $suppliers,
            'products' => $productsBySupplier->toArray()
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
        $event = Event::findOrFail($id);
        $request->validate([
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

        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $event = Event::find($id);
        $event->delete();
        return redirect()->back();
    }
}
