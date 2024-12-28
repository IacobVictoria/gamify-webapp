<?php

namespace App\Http\Controllers;

use App\Models\Survey;
use App\Services\NpsService;
use Illuminate\Http\Request;

class ChartController extends Controller
{
    protected $npsService;

    public function __construct(NpsService $npsService)
    {
        $this->npsService = $npsService;
    }

    public function getMonthlyNps()
    {
        $survey = Survey::where('is_published', true)->first();

        $npsData = $this->npsService->calculateMonthlyNps($survey->id);

        return response()->json($npsData);
    }
}
