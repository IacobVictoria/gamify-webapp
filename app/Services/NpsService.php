<?php

namespace App\Services;

use App\Models\SurveyQuestion;
use App\Models\SurveyResult;
class NpsService
{
    /**
     * Calculează NPS-ul pentru un anumit survey.
     *
     * @param string $surveyId
     * @return int
     */
    public function calculateNps($surveyId)
    {
        // Obține toate rezultatele pentru un survey specific
        $results = SurveyResult::where('survey_id', $surveyId)->get();

        $promoters = 0;
        $detractors = 0;
        $totalResponses = 0;

        foreach ($results as $result) {
            $responses = json_decode($result->responses, true);

            foreach ($responses as $questionId => $response) {
                $question = SurveyQuestion::find($questionId);

                if ($question) {
                    // Logică pentru întrebările de tip 'binary'
                    if ($question->type === 'binary') {
                        // Obține choice-ul care este 'is_promoter = true'
                        $promoterChoice = $question->choices()->where('is_promoter', true)->first();
                        if ($promoterChoice && $response === $promoterChoice->text) {
                            $promoters++;
                        } else {
                            $detractors++;
                        }
                    }
                    // Logică pentru întrebările de tip 'scale'
                    elseif ($question->type === 'scale') {
                        if ($response >= 9) {
                            $promoters++;
                        } elseif ($response <= 6) {
                            $detractors++;
                        }
                    }
                }
                if ($question->type === 'binary' || $question->type === 'scale') {
                    $totalResponses++;
                }
            }
        }

        // Calculează NPS-ul
        if ($totalResponses === 0) {
            return 0;
        }
     
        $nps = (($promoters - $detractors) / $totalResponses) * 100;

        return round($nps);
    }

}