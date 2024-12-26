<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyResult;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserSurveyController extends Controller
{
    public function index()
    {
        $survey = Survey::with(['questions.choices'])
            ->where('is_published', true)
            ->first();

        if (!$survey) {
            return response()->json(['message' => 'No published survey found'], 404);
        }
        return Inertia::render('NPS/Form', [
            'survey' => [
                'id' => $survey->id,
                'title' => $survey->title,
                'description' => $survey->description,
            ],
            'questions' => $survey->questions,
        ]);
    }
    public function store(Request $request)
    {
        // Validarea datelor primite
        $validated = $request->validate([
            'survey_id' => 'required|uuid',
            'responses' => 'required|array',
        ]);

        $responses = $validated['responses'];

        // Structura răspunsurilor JSON
        $formattedResponses = [];

        foreach ($responses as $questionId => $response) {
            $formattedResponses[$questionId] = $response;
        }

        // Creează un singur survey_result cu toate răspunsurile
        $surveyResult = SurveyResult::create([
            'id' => Uuid::uuid(),
            'survey_id' => $validated['survey_id'],
            'responses' => json_encode($formattedResponses), // Salvează răspunsurile ca JSON
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Feedback submitted successfully!', 'data' => $surveyResult], 201);
    }


}