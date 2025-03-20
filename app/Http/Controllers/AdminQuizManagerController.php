<?php

namespace App\Http\Controllers;

use App\Enums\UserQuizDifficulty;
use App\Http\Requests\AddQuizQuestionRequest;
use App\Models\UserQuiz;
use App\Models\UserQuizQuestion;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminQuizManagerController extends Controller
{
    public function show(string $id)
    {
        $quiz = UserQuiz::with('questions.answers')->find($id);

        return Inertia::render('Admin/UserQuizzes/QuizManager', [
            'quiz' => $quiz,
            'difficulties' => UserQuizDifficulty::values(),
        ]);
    }

    public function addQuestion(AddQuizQuestionRequest $request, string $quizId)
    {
        $quiz = UserQuiz::find($quizId);

        $validatedData = $request->validated();

        $question = $quiz->questions()->create([
            'id' => Uuid::uuid(),
            'question' => $validatedData['question']['text'],
            'score' => $validatedData['question']['score'] ?? 0,
        ]);

        foreach ($validatedData['question']['answers'] as $answerData) {
            $question->answers()->create([
                'id' => Uuid::uuid(),
                'answer' => $answerData['text'],
                'is_correct' => $answerData['isCorrect'],
            ]);
        }


        return redirect()->back();
    }

    public function updateQuestion(AddQuizQuestionRequest $request, $quizId)
    {
        $validated = $request->validated();

        $question = UserQuizQuestion::findOrFail($validated['question']['id']);

      
        $question->update([
            'question' => $validated['question']['text'],
            'score' => $validated['question']['score'],
        ]);


        foreach ($validated['question']['answers'] as $answer) {
            
            $existingAnswer = $question->answers()->find($answer['id']);

            if ($existingAnswer) {
                // update raspuns existent
                $existingAnswer->update([
                    'answer' => $answer['text'],
                    'is_correct' => $answer['isCorrect'],
                ]);
            } else {
                //  nou răspuns dacă ID-ul este null
                $question->answers()->create([
                    'id' => Uuid::uuid(),
                    'answer' => $answer['text'],
                    'is_correct' => $answer['isCorrect'],
                ]);
            }
        }


        return redirect()->back()->with('success', 'Question updated successfully!');
    }

}
