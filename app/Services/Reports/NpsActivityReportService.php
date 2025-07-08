<?php

namespace App\Services\Reports;

use App\Helpers\PeriodHelper;
use App\Models\SurveyChoice;
use App\Models\SurveyQuestion;
use App\Models\SurveyResult;
use Carbon\Carbon;

class NpsActivityReportService
{
    public function getReportByPeriod(string $period, Carbon $meetingDate): array
    {
        $dateRange = PeriodHelper::getPeriodRange($period, $meetingDate);
        $startDate = $dateRange['start_date'];
        $endDate = $dateRange['end_date'];

        return [
            'period' => $period,
            'startDate' => Carbon::parse($dateRange['start_date'])->format('d.m.Y'),
            'endDate' => Carbon::parse($dateRange['end_date'])->format('d.m.Y'),
            'npsData' => $this->calculateNpsByPeriod($startDate, $endDate),
            'binaryQuestions' => $this->getBinaryQuestionsStats($startDate, $endDate),
            'scaleQuestions' => $this->getScaleQuestionsStats($startDate, $endDate),
            'multipleChoiceQuestions' => $this->getMultipleChoiceStats($startDate, $endDate),
            'openEndedResponses' => $this->getOpenEndedResponses($startDate, $endDate),
        ];
    }

    /**
     * Calculează NPS-ul pentru perioada selectată.
     */
    private function calculateNpsByPeriod($startDate, $endDate): array
    {
        $monthlyData = [];

        // Obține toate rezultatele din perioada selectată
        $results = SurveyResult::whereBetween('created_at', [$startDate, $endDate])->get();

        foreach ($results as $result) {
            $month = Carbon::parse($result->created_at)->format('Y-m');

            if (!isset($monthlyData[$month])) {
                $monthlyData[$month] = [
                    'month' => $month,
                    'promoters' => 0,
                    'detractors' => 0,
                    'passives' => 0,
                    'totalResponses' => 0,
                ];
            }

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
                        } else {
                            $monthlyData[$month]['passives']++;
                        }
                    }
                    $monthlyData[$month]['totalResponses']++;
                }
            }
        }

        // Calculează NPS-ul pentru fiecare lună și returnează array indexat
        return array_values(array_map(function ($data) {
            $total = max(1, $data['totalResponses']);

            return [
                'month' => $data['month'],
                'nps' => round((($data['promoters'] - $data['detractors']) / $total) * 100, 2),
                'promotersCount' => $data['promoters'],
                'detractorsCount' => $data['detractors'],
                'passivesCount' => $data['passives'],
                'promotersPercentage' => round(($data['promoters'] / $total) * 100, 2),
                'detractorsPercentage' => round(($data['detractors'] / $total) * 100, 2),
                'passivesPercentage' => round(($data['passives'] / $total) * 100, 2),
                'totalResponses' => $data['totalResponses'],
            ];
        }, $monthlyData));
    }


    private function getBinaryQuestionsStats($startDate, $endDate)
    {
        $questions = SurveyQuestion::where('type', 'binary')->get();
        $stats = [];

        foreach ($questions as $question) {
            $yesCount = SurveyResult::whereBetween('created_at', [$startDate, $endDate])
                ->whereJsonContains("responses->{$question->id}", 'Yes')->count();
            $noCount = SurveyResult::whereBetween('created_at', [$startDate, $endDate])
                ->whereJsonContains("responses->{$question->id}", 'No')->count();
            $total = max(1, $yesCount + $noCount);
            $stats[$question->text] = [
                'yes' => round(($yesCount / $total) * 100, 2),
                'no' => round(($noCount / $total) * 100, 2)
            ];
        }

        return $stats;
    }

    private function getScaleQuestionsStats($startDate, $endDate)
    {
        $questions = SurveyQuestion::where('type', 'scale')->get();
        $stats = [];

        foreach ($questions as $question) {
            $average = SurveyResult::whereBetween('created_at', [$startDate, $endDate])
                ->whereNotNull("responses->{$question->id}")
                ->avg("responses->{$question->id}");
            $stats[$question->text] = round($average, 2);
        }

        return $stats;
    }

    private function getMultipleChoiceStats($startDate, $endDate)
    {
        $questions = SurveyQuestion::where('type', 'multiple_choice')->get();
        $stats = [];

        foreach ($questions as $question) {
            $choices = $question->choices()->pluck('id')->toArray();
            $counts = [];

            foreach ($choices as $choice) {
                $counts[$choice] = SurveyResult::whereBetween('created_at', [$startDate, $endDate])
                    ->whereJsonContains("responses->{$question->id}", $choice)->count();
            }

            arsort($counts);
            $topChoices = array_slice($counts, 0, 3, true);

            $choiceTexts = SurveyChoice::whereIn('id', array_keys($topChoices))
                ->pluck('text', 'id')
                ->toArray();

            $stats[$question->text] = array_map(fn($id) => $choiceTexts[$id] ?? "Unknown", array_keys($topChoices));
        }

        return $stats;
    }

    private function getOpenEndedResponses($startDate, $endDate)
    {
        $questions = SurveyQuestion::where('type', 'open')->get();
        $responses = [];

        $results = SurveyResult::whereBetween('created_at', [$startDate, $endDate])->get();

        foreach ($questions as $question) {
            $collectedResponses = [];

            foreach ($results as $result) {
                $data = json_decode($result->responses, true);

                if (isset($data[$question->id]) && !empty($data[$question->id])) {
                    $collectedResponses[] = $data[$question->id];
                }
            }

            $responses[$question->text] = array_values(array_slice($collectedResponses, 0, 5));
        }

        return $responses;
    }
}
