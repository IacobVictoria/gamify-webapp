<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Models\SurveyChoice;
use App\Models\SurveyQuestion;
use App\Models\SurveyResult;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class SurveyController extends Controller
{
    public function index()
    {
        $surveys = Survey::with('questions')->get();

        return Inertia::render('Admin/NPS_Admin/Index', [
            'surveys' => $surveys,
        ]);
    }
    public function showSurvey($id)
    {
        $survey = Survey::with('questions.choices')->findOrFail($id);

        return Inertia::render('Admin/NPS_Admin/SurveyDetail', [
            'survey' => $survey,
        ]);
    }
    public function deleteSurvey($id)
    {
        $survey = Survey::findOrFail($id);
        $survey->delete();

        return response()->json(['message' => 'Survey deleted successfully']);
    }
    public function updateSurvey(Request $request, $surveyId)
    {
        $validated = $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'is_published' => 'boolean',
        ]);

        if ($validated['is_published'] ?? false) {
            Survey::where('id', '!=', $surveyId)->update(['is_published' => false]);
        }

        $survey = Survey::findOrFail($surveyId);

        $survey->title = $validated['title'];
        $survey->description = $validated['description'] ?? $survey->description;
        $survey->is_published = $validated['is_published'] ?? false;

        $survey->save();

        return response()->json([
            'message' => 'Survey updated successfully!',
            'survey' => $survey,
        ]);
    }


    public function createSurvey()
    {
        $questions = SurveyQuestion::with('choices')
            ->whereNull('survey_id')
            ->get();

        return Inertia::render('Admin/NPS_Admin/Create', [
            'availableQuestions' => $questions, // Trimite doar întrebările disponibile care nu sunt atasate de un survey inca
        ]);
    }
    public function storeSurvey(Request $request)
    {
        $request->validate([
            'title' => 'required|string|max:255',
            'description' => 'nullable|string',
            'question_ids' => 'required|array',
            'question_ids.*' => 'exists:survey_questions,id', // Asigură-te că fiecare id de întrebare există în tabela questions
            'is_published' => 'boolean',
        ]);

        if ($request->has('is_published') && $request->is_published) {
            Survey::where('is_published', true)->update(['is_published' => false]);
        }

        $survey = Survey::create([
            'id' => Uuid::uuid(),
            'title' => $request->title,
            'description' => $request->description,
            'is_published' => $request->has('is_published') ? $request->is_published : false
        ]);

        // Actualizăm întrebările să aibă acest survey_id
        foreach ($request->question_ids as $question_id) {
            $question = SurveyQuestion::find($question_id);
            if ($question) {
                $question->survey_id = $survey->id;
                $question->save();
            }
        }

        return response()->json(['message' => 'Survey created and questions updated successfully!', 'survey' => $survey]);
    }

    public function storeQuestion(Request $request)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'type' => 'required|in:binary,scale,open,multiple_choice',
            'answers' => 'array', // Opțiuni de răspuns (pentru scale, binary, multiple_choice)
        ]);

        $question = SurveyQuestion::create([
            'id' => Uuid::uuid(),
            'survey_id' => null,
            'text' => $validated['text'],
            'type' => $validated['type'],
        ]);

        if (in_array($validated['type'], ['binary', 'scale', 'multiple_choice'])) {
            foreach ($validated['answers'] as $answer) {
                SurveyChoice::create([
                    'id' => Uuid::uuid(),
                    'question_id' => $question->id,
                    'text' => $answer['text'],
                    'is_promoter' => $answer['is_promoter'] ?? false,
                ]);
            }
        }

        return response()->json(['message' => 'Question saved successfully', 'question' => $question]);
    }

    public function updateQuestion(Request $request, $questionId)
    {
        $validated = $request->validate([
            'text' => 'required|string',
            'type' => 'required|in:binary,scale,open,multiple_choice',
            'choices' => 'array',
            'choices.*.text' => 'required|string',
            'choices.*.is_promoter' => 'boolean',
        ]);

        $question = SurveyQuestion::findOrFail($questionId);

        $question->text = $validated['text'];
        $question->type = $validated['type'];
        $question->save();

        if (in_array($validated['type'], ['binary', 'scale', 'multiple_choice'])) {
            // Șterge toate răspunsurile existente asociate întrebării
            SurveyChoice::where('question_id', $question->id)->delete();

            // Creează răspunsurile noi
            foreach ($validated['choices'] as $choice) {
                SurveyChoice::create([
                    'id' => Uuid::uuid(),
                    'question_id' => $question->id,
                    'text' => $choice['text'],
                    'is_promoter' => $choice['is_promoter'] ?? false,
                ]);
            }
        }

        return response()->json(['message' => 'Question updated successfully!', 'question' => $question]);
    }
    public function deleteQuestion($questionId)
    {
        $question = SurveyQuestion::findOrFail($questionId);

        $survey_id = $question->survey_id;
        $survey_results = SurveyResult::where('survey_id', $survey_id)->get();
        foreach ($survey_results as $result) {
            // stergere din json ul "responses" din surveyResults 
            $responses = json_decode($result->responses, true);
            if (isset($responses[$questionId])) {
                unset($responses[$questionId]);
            }
            $result->responses = json_encode($responses);
            $result->save();

        }
        $question->delete();

        return response()->json(['message' => 'Question deleted successfully!']);
    }

}
