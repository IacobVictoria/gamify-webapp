<?php

namespace App\Http\Controllers;

use App\Models\Event;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Route;
use Inertia\Inertia;

class WelcomeController extends Controller
{
    public function index()
    {
        $activeDiscounts = Event::where('type', 'discount')
            ->where('status', 'OPEN')
            ->where('start', '<=', now())
            ->where('end', '>=', now())
            ->where('is_published', true)
            ->get()
            ->map(function ($discount) {
                // Decode the details JSON
                $discount->details = json_decode($discount->details, true);
                return $discount;
            });
        return Inertia::render('Welcome', [
            'canLogin' => Route::has('login'),
            'canRegister' => Route::has('register'),
            'laravelVersion' => \Illuminate\Foundation\Application::VERSION,
            'phpVersion' => PHP_VERSION,
            'activeDiscounts' => $activeDiscounts,
        ]);
    }
}
