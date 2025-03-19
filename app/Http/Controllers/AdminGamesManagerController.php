<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminGamesManagerController extends Controller
{
    public function index(){
        return Inertia::render('Admin/GamesManager/Index');
    }
}
