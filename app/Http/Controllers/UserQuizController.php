<?php

namespace App\Http\Controllers;

use App\Models\UserQuiz;
use App\Models\UserQuizAnswer;
use App\Models\UserQuizResponse;
use App\Models\UserQuizResult;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Inertia\Inertia;

class UserQuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $user = Auth::user();
        $quizzes = UserQuiz::all();

        $groupedQuizzes = $quizzes->groupBy('difficulty')->map(function ($quizzes) use ($user) {
            return $quizzes->map(function ($quiz) use ($user) {
 
                $result = UserQuizResult::where('user_id', $user->id)
                    ->where('quiz_id', $quiz->id)
                    ->first();
    
                return [
                    'quizData' => $quiz,
                    'is_locked' => $result ? $result->is_locked : false, 
                ];
            });
        });
        return Inertia::render('User/Quizzes/Index', [
            'quizzes' => $groupedQuizzes
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
        $quiz = UserQuiz::with([
            'questions.answers' => function ($query) {
                $query->orderByRaw('RAND()');
            }
        ])->findOrFail($quizId);

        $quiz->questions = $quiz->questions->shuffle();

        return Inertia::render('User/Quizzes/Show', [
            'quiz' => $quiz
        ]);
    }

    public function retryQuiz(Request $request)
    {
        $quizId = $request->input('quiz_id');
        $userId = $request->input('user_id');
        $score = $request->input('score');
        $responses = $request->input('responses');

        // Verifică dacă există deja un rezultat pentru acest quiz și user
        $quizResult = UserQuizResult::where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->first();

        if (!$quizResult) {
            // Dacă nu există un rezultat anterior, creează unul nou cu attempt_number = 1
            $quizResult = new UserQuizResult([
                'id' => Uuid::uuid(),
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'attempt_number' => 1,
                'total_score' => $score,
                'is_locked' => false
            ]);
        } else {
            // Dacă există deja un rezultat, incrementează attempt_number
            $quizResult->attempt_number += 1;
            $quizResult->total_score = $score;
        }

        // Blochează quiz-ul dacă numărul de încercări depășește 3
        if ($quizResult->attempt_number > 3) {
            $quizResult->is_locked = true;
        }

        $quizResult->save();

        // Salvează răspunsurile utilizatorului
        foreach ($responses as $response) {
            // Caută dacă există deja un răspuns
            $existingResponse = UserQuizResponse::where([
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'question_id' => $response['questionId']
            ])->first();

            if ($existingResponse) {
                // Actualizează răspunsul existent
                $existingResponse->update([
                    'answer_id' => $response['answerId'],
                    'is_correct' => UserQuizAnswer::find($response['answerId'])->is_correct,
                ]);
            } else {
                // Creează un nou răspuns
                UserQuizResponse::create([
                    'id' => Uuid::uuid(), // Generează un UUID nou
                    'user_id' => $userId,
                    'quiz_id' => $quizId,
                    'question_id' => $response['questionId'],
                    'answer_id' => $response['answerId'],
                    'is_correct' => UserQuizAnswer::find($response['answerId'])->is_correct,
                ]);
            }
        }

        return redirect()->route('user.quiz.show', ['quizId' => $quizId]);

    }

    public function lockQuiz(Request $request)
    {
        $quizId = $request->input('quiz_id');
        $userId = $request->input('user_id');
        $score = $request->input('score');
        $responses = $request->input('responses');

        // Verifică dacă există deja un rezultat pentru acest quiz și user
        $quizResult = UserQuizResult::where('user_id', $userId)
            ->where('quiz_id', $quizId)
            ->first();

        if (!$quizResult) {
            // Dacă nu există un rezultat anterior, creează unul nou cu attempt_number = 1
            $quizResult = new UserQuizResult([
                'id' => Uuid::uuid(),
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'attempt_number' => 1,
                'total_score' => $score,
                'is_locked' => true
            ]);
        } else {
            // Dacă există deja un rezultat, setează attempt_number și blochează quiz-ul
            $quizResult->attempt_number += 1;
            $quizResult->total_score = $score;
            $quizResult->is_locked = true;
        }

        $quizResult->save();

        // Salvează răspunsurile utilizatorului
        foreach ($responses as $response) {
            // Caută dacă există deja un răspuns
            $existingResponse = UserQuizResponse::where([
                'user_id' => $userId,
                'quiz_id' => $quizId,
                'question_id' => $response['questionId']
            ])->first();

            if ($existingResponse) {
                // Actualizează răspunsul existent
                $existingResponse->update([
                    'answer_id' => $response['answerId'],
                    'is_correct' => UserQuizAnswer::find($response['answerId'])->is_correct,
                ]);
            } else {
                // Creează un nou răspuns
                UserQuizResponse::create([
                    'id' => Uuid::uuid(), // Generează un UUID nou
                    'user_id' => $userId,
                    'quiz_id' => $quizId,
                    'question_id' => $response['questionId'],
                    'answer_id' => $response['answerId'],
                    'is_correct' => UserQuizAnswer::find($response['answerId'])->is_correct,
                ]);
            }
        }

        return redirect()->route('user.quiz.show', ['quizId' => $quizId]);
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
