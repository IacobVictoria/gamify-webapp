<?php

namespace App\Http\Controllers;

use App\Models\HangmanSession;
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

    public function historyHangman()
    {
        $user = Auth()->user();
        $userResults = HangmanSession::where('creator_id', $user->id)
            ->orWhere('opponent_id', $user->id)
            ->orderBy('created_at', 'desc')
            ->get()
            ->map(function ($session) use ($user) {
                return [
                    'id' => $session->id,
                    'is_creator' => $session->creator_id == $user->id,
                    'is_opponent' => $session->opponent_id == $user->id,
                    'creator_id' => $session->creator_id,
                    'opponent_id' => $session->opponent_id,
                    'creator_name' => $session->creator->name,
                    'opponent_name' => $session->opponent->name,
                    'word_for_creator' => $session->word_for_creator,
                    'word_for_opponent' => $session->word_for_opponent,
                    'hint_for_creator' => $session->hint_for_creator,
                    'hint_for_opponent' => $session->hint_for_opponent,
                    'mistakes_creator' => $session->mistakes_creator,
                    'mistakes_opponent' => $session->mistakes_opponent,
                    'scores' => json_decode($session->scores, true),
                    'updated_at' => $session->updated_at
                ];
            });

        return Inertia::render('User/UserDashboard/ExploreGames/HangmanHistory', [
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
