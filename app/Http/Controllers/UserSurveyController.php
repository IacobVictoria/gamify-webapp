<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyChoice;
use App\Models\SurveyQuestion;
use App\Models\SurveyResult;
use App\Services\DiscordService;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class UserSurveyController extends Controller
{
    public $discordService;
    public function __construct(DiscordService $discordService)
    {
        $this->discordService = $discordService;
    }
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
        $message = $this->createDiscordMessage($validated['survey_id'], $responses);
        // Trimite mesajul către Discord
        $this->discordService->sendMessage($message);

        return response()->json(['message' => 'Feedback submitted successfully!', 'data' => $surveyResult], 201);
    }
    private function createDiscordMessage(string $surveyId, array $responses): string
    {
        $questions = SurveyQuestion::whereIn('id', array_keys($responses))->get();
        $survey = Survey::find($surveyId);
        $message = "**New Survey Submission**\n";
        $message .= "Survey ID: `$surveyId`\n\n";
        $message .= "Survey Title: `$survey->title`\n\n";

        foreach ($questions as $question) {
            $response = $responses[$question->id] ?? 'No Response';
            $message .= "**Q: {$question->text}**\n";
            if ($question->type === "multiple_choice") {
                $responseText= SurveyChoice::find($response)->text;
                $message .= "A: `$responseText`\n\n";
            } else {
                $message .= "A: `$response`\n\n";
            }
        }

        return $message;
    }
}