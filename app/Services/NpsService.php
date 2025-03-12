<?php

namespace App\Services;

use App\Factories\NpsStrategyFactory;
use App\Factories\PdfGeneratorFactory;
use App\Factories\StorageStrategyFactory;
use App\Models\Survey;
use App\Models\SurveyChoice;
use App\Models\SurveyQuestion;
use App\Models\SurveyResult;
use Carbon\Carbon;
use Illuminate\Support\Facades\Storage;
class NpsService
{
    protected array $strategies;

    public function __construct()
    {
        // Inițializăm toate strategiile disponibile
        $this->strategies = [
            'monthly' => NpsStrategyFactory::create('monthly'),
            'general' => NpsStrategyFactory::create('general'),
        ];
    }

    /**
     * Calculează NPS-ul general (pe toate datele)
     */
    public function calculateNps(string $surveyId): array
    {
        return $this->strategies['general']->calculate($surveyId);
    }

    /**
     * Calculează NPS-ul lunar
     */
    public function calculateMonthlyNps(string $surveyId): array
    {
        return $this->strategies['monthly']->calculate($surveyId);
    }

    public function generateNpsReportPdf(string $surveyId, string $surveyPeriod)
    {
        $survey = Survey::findOrFail($surveyId);
        $monthlyNpsData = $this->calculateMonthlyNps($surveyId);

        // Preia datele din luna specificată
        $currentMonthData = collect($monthlyNpsData)->firstWhere('month', $surveyPeriod) ?? [
            'nps' => 0,
            'promoters' => 0,
            'detractors' => 0,
            'totalResponses' => 0
        ];

        $prevPeriod = Carbon::parse($surveyPeriod)->subMonth()->format('Y-m');
        $prevMonthData = collect($monthlyNpsData)->firstWhere('month', $prevPeriod) ?? ['nps' => 0];

        $storageStrategy = StorageStrategyFactory::create('s3');
        $generator = PdfGeneratorFactory::create('nps_report', $storageStrategy);

        $data = [
            'surveyTitle' => $survey->title,
            'surveyId' => $survey->id,
            'surveyPeriod' => $surveyPeriod,
            'totalResponses' => $currentMonthData['totalResponses'],
            'nps' => $currentMonthData['nps'],
            'prevNps' => $prevMonthData['nps'] ?? null,
            'promotersCount' => $currentMonthData['promoters'],
            'detractorsCount' => $currentMonthData['detractors'],
            'passivesCount' => $currentMonthData['promoters'] - $currentMonthData['detractors'],
            'promotersPercentage' => round(($currentMonthData['promoters'] / max(1, $currentMonthData['totalResponses'])) * 100, 2),
            'detractorsPercentage' => round(($currentMonthData['detractors'] / max(1, $currentMonthData['totalResponses'])) * 100, 2),
            'passivesPercentage' => round((($currentMonthData['promoters'] - $currentMonthData['detractors']) / max(1, $currentMonthData['totalResponses'])) * 100, 2),
            'npsEvolution' => $prevMonthData ? "NPS changed by " . round($currentMonthData['nps'] - $prevMonthData['nps'], 2) : "No previous data",
            'binaryQuestions' => $this->getBinaryQuestionsStats($surveyId, $surveyPeriod),
            'scaleQuestions' => $this->getScaleQuestionsStats($surveyId, $surveyPeriod),
            'multipleChoiceQuestions' => $this->getMultipleChoiceStats($surveyId, $surveyPeriod),
            'openEndedResponses' => $this->getOpenEndedResponses($surveyId, $surveyPeriod),
        ];

        return $generator->generatePdf($data);
    }

    private function getBinaryQuestionsStats($surveyId, $surveyPeriod)
    {
        $questions = SurveyQuestion::where('survey_id', $surveyId)->where('type', 'binary')->get();
        $stats = [];

        foreach ($questions as $question) {
            $yesCount = SurveyResult::whereJsonContains("responses->{$question->id}", 'Yes')->count();
            $noCount = SurveyResult::whereJsonContains("responses->{$question->id}", 'No')->count();
            $total = max(1, $yesCount + $noCount);
            $stats[$question->text] = [
                'yes' => round(($yesCount / $total) * 100, 2),
                'no' => round(($noCount / $total) * 100, 2)
            ];
        }

        return $stats;
    }

    private function getScaleQuestionsStats($surveyId, $surveyPeriod)
    {
        $questions = SurveyQuestion::where('survey_id', $surveyId)->where('type', 'scale')->get();
        $stats = [];

        foreach ($questions as $question) {
            $average = SurveyResult::whereNotNull("responses->{$question->id}")->avg("responses->{$question->id}");
            $stats[$question->text] = round($average, 2);
        }

        return $stats;
    }

    private function getMultipleChoiceStats($surveyId, $surveyPeriod)
    {
        $questions = SurveyQuestion::where('survey_id', $surveyId)->where('type', 'multiple_choice')->get();
        $stats = [];

        foreach ($questions as $question) {
            $choices = $question->choices()->pluck('id')->toArray();
            $counts = [];

            foreach ($choices as $choice) {
                $counts[$choice] = SurveyResult::whereJsonContains("responses->{$question->id}", $choice)->count();
            }

            arsort($counts);
            $topChoices = array_slice($counts, 0, 3, true); // Primele 3 opțiuni

            // Înlocuiește ID-urile cu textele opțiunilor
            $choiceTexts = SurveyChoice::whereIn('id', array_keys($topChoices))
                ->pluck('text', 'id')
                ->toArray();

            // Construiește statistica finală cu textele opțiunilor
            $stats[$question->text] = array_map(fn($id) => $choiceTexts[$id] ?? "Unknown", array_keys($topChoices));
        }

        return $stats;
    }

    private function getOpenEndedResponses($surveyId, $surveyPeriod)
    {
        $questions = SurveyQuestion::where('survey_id', $surveyId)
            ->where('type', 'open')
            ->get();

        $responses = [];

        // Filtrăm rezultatele doar pentru luna specificată
        $results = SurveyResult::where('survey_id', $surveyId)
            ->get();

        foreach ($questions as $question) {
            $collectedResponses = [];

            foreach ($results as $result) {
                $data = json_decode($result->responses, true);

                if (isset($data[$question->id]) && !empty($data[$question->id])) {
                    $collectedResponses[] = $data[$question->id];
                }
            }

            // Stocăm doar primele 5 răspunsuri pentru fiecare întrebare
            $responses[$question->text] = array_values(array_slice($collectedResponses, 0, 5));
        }

        return $responses;
    }



    public function sendNpsReportToDiscord()
    {
        $lastMonth = Carbon::now()->format('Y-m');
        $survey = Survey::where('is_published', true)->first();

        // Generează PDF-ul pentru luna trecută
        $pdfPath = $this->generateNpsReportPdf($survey->id, $lastMonth);

        // Extragem numele fișierului din calea S3
        $fileName = basename($pdfPath);
        $localPath = storage_path("app/temp/{$fileName}"); // Salvăm PDF-ul temporar

        // Creăm folderul temp dacă nu există
        if (!file_exists(storage_path('app/temp'))) {
            mkdir(storage_path('app/temp'), 0777, true);
        }

        // Descărcăm PDF-ul din S3 și îl salvăm local

        $fileContent = Storage::disk('s3')->get(str_replace(Storage::disk('s3')->url('/'), '', $pdfPath));
        file_put_contents($localPath, $fileContent);


        // Trimitem PDF-ul prin DiscordService
        $discordService = new DiscordService();
        $discordService->sendFile("📊 Monthly NPS Report for *{$lastMonth}* is ready! 📩", "temp/{$fileName}");

        // Ștergem fișierul temporar
        unlink($localPath);
    }


}