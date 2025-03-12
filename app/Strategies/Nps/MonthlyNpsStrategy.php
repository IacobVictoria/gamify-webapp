<?php
namespace App\Strategies\Nps;

use App\Interfaces\NpsCalculationStrategyInterface;
use App\Models\SurveyQuestion;
use App\Models\SurveyResult;
use Carbon\Carbon;

class MonthlyNpsStrategy implements NpsCalculationStrategyInterface
{
    public function calculate(string $surveyId):array
    {
        // Obține toate rezultatele pentru un survey specific, grupate pe lună
        $results = SurveyResult::where('survey_id', $surveyId)->get();
    
        $monthlyData = [];
    
        foreach ($results as $result) {
            $month = Carbon::parse($result->created_at)->format('Y-m');
    
            if (!isset($monthlyData[$month])) {
                $monthlyData[$month] = [
                    'month' => $month,
                    'promoters' => 0,
                    'detractors' => 0,
                    'totalResponses' => 0,
                    'entries' => 0, // Numărul de SurveyResult pentru luna respectivă
                ];
            }
    
            $monthlyData[$month]['entries']++;
    
            $responses = json_decode($result->responses, true);
    
            foreach ($responses as $questionId => $response) {
                $question = SurveyQuestion::find($questionId);
    
                if ($question && in_array($question->type, ['binary', 'scale'])) {
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
                    $monthlyData[$month]['totalResponses']++;
                }
            }
        }
    
        // Calculează NPS-ul pentru fiecare lună și returnează array indexat (compatibil Vue.js)
        return array_values(array_map(function ($data) {
            $data['nps'] = $data['totalResponses'] > 0
                ? round((($data['promoters'] - $data['detractors']) / $data['totalResponses']) * 100, 2)
                : 0;
    
            return $data;
        }, $monthlyData));
    }
}
