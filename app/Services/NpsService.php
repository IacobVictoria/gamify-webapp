<?php

namespace App\Services;

use App\Models\SurveyQuestion;
use App\Models\SurveyResult;
use Carbon\Carbon;
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

     /**
     * Calculează NPS-ul și răspunsurile pentru fiecare lună.
     *
     * @param string $surveyId
     * @return array
     */
    public function calculateMonthlyNps($surveyId)
    {
        // Obține toate rezultatele pentru un survey specific
        $results = SurveyResult::where('survey_id', $surveyId)->get();
    
        $monthlyData = [];
    
        foreach ($results as $result) {
            $month = Carbon::parse($result->created_at)->format('Y-m');
            if (!isset($monthlyData[$month])) {
                $monthlyData[$month] = [
                    'promoters' => 0,
                    'detractors' => 0,
                    'totalResponses' => 0,
                    'entries' => 0, // Numărul de SurveyResult în luna respectivă
                ];
            }
    
            // Incrementăm numărul de intrări pentru luna respectivă
            $monthlyData[$month]['entries']++;
    
            $responses = json_decode($result->responses, true);
    
            foreach ($responses as $questionId => $response) {
                $question = SurveyQuestion::find($questionId);
    
                if ($question) {
                    if ($question->type === 'binary') {
                        $promoterChoice = $question->choices()->where('is_promoter', true)->first();
                        if ($promoterChoice && $response === $promoterChoice->text) {
                            $monthlyData[$month]['promoters']++;
                        } else {
                            $monthlyData[$month]['detractors']++;
                        }
                    } elseif ($question->type === 'scale') {
                        if ($response >= 9) {
                            $monthlyData[$month]['promoters']++;
                        } elseif ($response <= 6) {
                            $monthlyData[$month]['detractors']++;
                        }
                    }
                }
    
                if ($question->type === 'binary' || $question->type === 'scale') {
                    $monthlyData[$month]['totalResponses']++;
                }
            }
        }
    
        // Calculează NPS-ul pentru fiecare lună
        $resultData = [];
        foreach ($monthlyData as $month => $data) {
            $nps = $data['totalResponses'] > 0
                ? (($data['promoters'] - $data['detractors']) / $data['totalResponses']) * 100
                : 0;
    
            $resultData[] = [
                'month' => $month,
                'nps' => round($nps, 2),
                'responses' => $data['totalResponses'], // Numărul total de răspunsuri
                'entries' => $data['entries'],         // Numărul de SurveyResult pentru luna respectivă
            ];
        }
    
        // Sortează după lună
        usort($resultData, fn($a, $b) => strcmp($a['month'], $b['month']));
    
        return $resultData;
    }
    
    
}