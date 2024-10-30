<?php

namespace App\Http\Controllers;

use App\Http\Requests\UserQuizRequest;
use App\Models\UserQuiz;
use App\Models\UserQuizAnswer;
use App\Models\UserQuizQuestion;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminQuizController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters', []);
        $quizQuery = UserQuiz::with('questions.answers');

        if (isset($filters['searchTitle'])) {
            $quizQuery->where('title', 'like', '%' . $filters['searchTitle'] . '%');
        }
        if (isset($filters['searchQuestion'])) {
            $quizQuery->whereHas('questions', function ($query) use ($filters) {
                $query->where('question', 'like', '%' . $filters['searchQuestion'] . '%');
            });
        }

        $quizzes = $quizQuery->paginate(10)->through(function ($quiz) {
            return [
                'id' => $quiz->id,
                'title' => $quiz->title,
                'difficulty' => $quiz->difficulty,
                'created_at' => $quiz->created_at->format('Y-m-d'),
                'max_score' => $quiz->questions->sum('score'),
                'questions' => $quiz->questions->map(function ($question) {
                    return [
                        'id' => $question->id,
                        'question' => $question->question,
                        'score' => $question->score,
                        'answers' => $question->answers
                    ];
                })

            ];
        });


        return Inertia::render('Admin/UserQuizzes/Index', [
            'quizzes' => $quizzes,
            'prevFilters' => $filters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/UserQuizzes/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(UserQuizRequest $request)
    {
        $validated = $request->validated();

        // Create quiz
        $quiz = UserQuiz::create([
            'id' => Uuid::uuid(),
            'title' => $validated['title'],
            'description' => $validated['description']

        ]);

        // Create questions and answers
        foreach ($validated['questions'] as $questionData) {
            $newQuestion = UserQuizQuestion::create([
                'id' => Uuid::uuid(),
                'quiz_id' => $quiz->id,
                'question' => $questionData['text'],
                'score' => $questionData['score'] ?? 0,
            ]);

            // Create answers for the question
            foreach ($questionData['answers'] as $answerData) {
                UserQuizAnswer::create([
                    'id' => Uuid::uuid(),
                    'question_id' => $newQuestion->id,
                    'answer' => $answerData['text'],
                    'is_correct' => $answerData['isCorrect'],
                ]);
            }
        }


        return redirect()->route('admin.user_quiz.index')->with('success', 'Quiz created successfully!');
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

    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $quizId)
    {
        $validatedData = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'required|string|max:255'
        ]);

        $quiz = UserQuiz::find($quizId);
        $quiz->update([
            'title' => $validatedData['title'],
            'description' => $validatedData['description']
        ]);

        return redirect()->back()->with('Succes Updated the quiz');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $quizId)
    {
        $quiz = UserQuiz::find($quizId);

        $quiz->delete();

        return redirect()->route('admin.user_quiz.index')
            ->with('success', 'Quiz deleted successfully!');
    }
}
