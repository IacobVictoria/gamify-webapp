<?php

namespace App\Http\Controllers;

use App\Models\Participant;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserFavoriteActivityController extends Controller
{
    public function index()
    {
        $favorites = Participant::with('activity')
            ->where('user_id', Auth::id())
            ->where('is_favorite', true)
            ->get()
            ->pluck('activity') 
            ->filter(); // eliminăm null-uri dacă s-au șters activități

        return Inertia::render('User/FavoriteActivities/Index', [
            'favorites' => $favorites,
        ]);
    }
}
