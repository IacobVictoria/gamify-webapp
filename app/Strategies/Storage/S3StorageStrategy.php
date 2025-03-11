<?php
namespace App\Strategies\Storage;

use App\Interfaces\StorageStrategyInterface;
use Illuminate\Support\Facades\Storage;

class S3StorageStrategy implements StorageStrategyInterface
{
    public function save(string $pdfContent, string $path): string
    {
        Storage::disk('s3')->put($path, $pdfContent, 'public');
        return Storage::disk('s3')->url($path);
    }
}
