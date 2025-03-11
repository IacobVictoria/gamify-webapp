<?php
namespace App\Interfaces;

interface StorageStrategyInterface
{
    public function save(string $pdfContent, string $path): string;
}
