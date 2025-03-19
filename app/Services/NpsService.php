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

}