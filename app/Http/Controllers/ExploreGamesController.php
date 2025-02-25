<?php

namespace App\Http\Controllers;

use App\Models\UserQuizResponse;
use App\Models\UserQuizResult;
use Illuminate\Http\Request;
use Inertia\Inertia;
use Illuminate\Support\Facades\Auth;

class ExploreGamesController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return Inertia::render('User/UserDashboard/ExploreGames/Index', [
         
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
    public function show(string $quizId)
    {

        $user = Auth()->user();

        $quizResult = UserQuizResult::with(['quiz.questions.answers'])
            ->where('user_id', $user->id)
            ->where('quiz_id', $quizId)
            ->orderBy('attempt_number', 'desc')
            ->firstOrFail();

        // Obținem toate răspunsurile date de utilizator pentru acest quiz
        $userResponses = UserQuizResponse::where('user_id', $user->id)
            ->where('quiz_id', $quizId)
            ->get();

        return Inertia::render('User/UserDashboard/ExploreGames/QuizDetail', [
            'quiz' => $quizResult->quiz,
            'responses' => $userResponses,
            'quizResult' => $quizResult
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function historyQuiz()
    {
        $user = Auth()->user();
        $userResults = UserQuizResult::with('quiz:id,title')
            ->where('user_id', $user->id)
            ->orderBy('date', 'desc')
            ->get();

        return Inertia::render('User/UserDashboard/ExploreGames/QuizHistory', [
            'userResults' => $userResults
        ]);
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
