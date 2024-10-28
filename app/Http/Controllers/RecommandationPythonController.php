<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\Http;
use Inertia\Inertia;

class RecommandationPythonController extends Controller
{
    public function index($userId)
    {
       
            $response = Http::get("http://127.0.0.1:5000/api/recommendations/{$userId}");
        
            if ($response->successful()) {
              
                $recommendations = $response->json();
            } 


        return Inertia::render('User/Recommendations', [
            'recommendations' => $recommendations,
        ]);
    
}
}
