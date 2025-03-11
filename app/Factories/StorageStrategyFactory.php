<?php
namespace App\Factories;

use App\Interfaces\StorageStrategyInterface;
use App\Strategies\Storage\S3StorageStrategy;



class StorageStrategyFactory
{
    public static function create(string $type):StorageStrategyInterface
    {
        return match ($type) {
            's3' => new S3StorageStrategy(),
            default => throw new \Exception("Strategie de stocare invalidă: {$type}")
        };
    }
}
