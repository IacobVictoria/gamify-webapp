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
    //Rezultate
    public function storeResults(Request $request)
    {
        $validated = $request->validate([
            'survey_id' => 'required|uuid',
            'responses' => 'required|array',
        ]);

        $responses = $validated['responses'];


        $formattedResponses = [];

        foreach ($responses as $questionId => $response) {
            $formattedResponses[$questionId] = $response;
        }

        $surveyResult = SurveyResult::create([
            'id' => Uuid::uuid(),
            'survey_id' => $validated['survey_id'],
            'responses' => json_encode($formattedResponses), 
            'created_at' => now(),
            'updated_at' => now(),
        ]);

        return response()->json(['message' => 'Feedback submitted successfully!', 'data' => $surveyResult], 201);
    }
}