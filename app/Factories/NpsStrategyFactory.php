<?php
namespace App\Factories;

use App\Interfaces\NpsCalculationStrategyInterface;
use App\Strategies\Nps\GeneralNpsStrategy;
use App\Strategies\Nps\MonthlyNpsStrategy;


class NpsStrategyFactory
{
    public static function create(string $type): NpsCalculationStrategyInterface
    {
        return match ($type) {
            'monthly' => new MonthlyNpsStrategy(),
            'general'=>new GeneralNpsStrategy(),
            default => throw new \InvalidArgumentException("Invalid NPS strategy type"),
        };
    }
}
