<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Inertia\Inertia;

class EventController extends Controller
{

    public function index()
    {
        // Obține reducerile active (discounts)
        $activeDiscounts = Event::where('type', 'discount')
            ->where('status', 'OPEN')
            ->where('start', '<=', now())  // Reduceri care au început deja
            ->where('end', '>=', now())
            ->where('is_published', true)  // Reduceri care nu au expirat
            ->get();

        foreach ($activeDiscounts as $event) {
            if ($event->type === 'discount' && $event->details) {
                $event->details = json_decode($event->details, true);
            }
        }


        return Inertia::render('Events/Index', [
            'activeDiscounts' => $activeDiscounts,
        ]);
    }
  
}
