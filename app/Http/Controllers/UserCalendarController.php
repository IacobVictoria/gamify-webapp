<?php

namespace App\Http\Controllers;

use App\Models\Event;
use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserCalendarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $events = Event::where(function ($query) {
            $query->where('type', 'discount')
                ->orWhere('type', 'event');
        })
            ->where('status', 'OPEN')
            ->where('start', '<=', now())
            ->where('end', '>=', now())
            ->where('is_published', 1)
            ->get();
      
        $categories = Product::distinct()->pluck('category');
        return Inertia::render('User/Calendar/Index', [
            'events' => $events,
            'categories' => $categories
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
