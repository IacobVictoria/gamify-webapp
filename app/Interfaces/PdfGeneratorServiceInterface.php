<?php

namespace App\Interfaces;
interface PdfGeneratorServiceInterface
{
    public function generatePdf(array $data): string;
}
