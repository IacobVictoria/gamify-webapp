<?php

namespace App\Strategies\Nps;

use App\Interfaces\NpsCalculationStrategyInterface;
use App\Models\SurveyQuestion;
use App\Models\SurveyResult;

class GeneralNpsStrategy implements NpsCalculationStrategyInterface
{
    public function calculate(string $surveyId): array
    {
        $results = SurveyResult::where('survey_id', $surveyId)->get();

        $promoters = 0;
        $detractors = 0;
        $totalResponses = 0;

        foreach ($results as $result) {
            $responses = json_decode($result->responses, true);

            foreach ($responses as $questionId => $response) {
                $question = SurveyQuestion::find($questionId);
                if (!$question) continue;

                if ($question->type === 'binary') {
                    $promoterChoice = $question->choices()->where('is_promoter', true)->first();
                    if ($promoterChoice && $response === $promoterChoice->text) {
                        $promoters++;
                    } else {
                        $detractors++;
                    }
                } elseif ($question->type === 'scale') {
                    if ($response >= 9) {
                        $promoters++;
                    } elseif ($response <= 6) {
                        $detractors++;
                    }
                }

                if (in_array($question->type, ['binary', 'scale'])) {
                    $totalResponses++;
                }
            }
        }

        // Calculează NPS-ul
        $nps = $totalResponses > 0
            ? round((($promoters - $detractors) / $totalResponses) * 100, 2)
            : 0;

        return [
            'nps' => $nps,
            'promoters' => $promoters,
            'detractors' => $detractors,
            'totalResponses' => $totalResponses
        ];
    }
}