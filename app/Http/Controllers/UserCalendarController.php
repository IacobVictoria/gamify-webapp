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
        // $events = Event::where(function ($query) {
        //     $query->where('type', 'discount')
        //           ->where('start', '<=', now()) // Discount începe înainte de acum
        //           ->where('end', '>=', now()); // Discount trebuie să fie încă activ
        // })
        // ->orWhere(function ($query) {
        //     $query->where('type', 'event')
        //           ->where('end', '>=', now()) // Evenimentul trebuie să nu fi expirat
        //           ->whereHas('participants', function ($query) {
        //               $query->where('user_id', auth()->id()); // Verifică dacă utilizatorul este participant
        //           });
        // })
        // ->where('status', 'OPEN')  // Evenimentele trebuie să fie deschise
        // ->where('is_published', 1) // Evenimentele trebuie să fie publicate
        // ->with('qrCode')
        // ->get();


        $events = Event::where(function ($query) {
            $query->where('type', 'discount')
                  ->where('start', '<=', now()) // Discount începe înainte de acum
                  ->where('end', '>=', now()); // Discount trebuie să fie încă activ
        })
        ->orWhere(function ($query) {
            $query->where('type', 'event')
                  ->whereHas('participants', function ($query) {
                      $query->where('user_id', auth()->id()); // Verifică dacă utilizatorul este participant
                  });
        })
        ->where('is_published', 1) // Evenimentele trebuie să fie publicate
        ->with('qrCode')
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
