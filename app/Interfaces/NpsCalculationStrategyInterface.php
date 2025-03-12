<?php

namespace App\Interfaces;

interface NpsCalculationStrategyInterface
{
    public function calculate(string $surveyId): array;
}
