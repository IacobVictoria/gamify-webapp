<?php
namespace App\Services\PdfGenerators;

use App\Interfaces\PdfGeneratorServiceInterface;
use App\Interfaces\StorageStrategyInterface;
use Dompdf\Dompdf;
use Dompdf\Options;

abstract class AbstractPdfGeneratorService implements PdfGeneratorServiceInterface
{
    protected StorageStrategyInterface $storageStrategy;

    public function __construct(StorageStrategyInterface $storageStrategy)
    {
        $this->storageStrategy = $storageStrategy;
    }

    protected function generatePdfContent(string $view, array $data): string
    {
        $options = new Options();
        $options->set('defaultFont', 'Arial');
        $dompdf = new Dompdf($options);

        $html = view($view, $data)->render();
        $dompdf->loadHtml($html);
        $dompdf->setPaper('A4', 'portrait');
        $dompdf->render();

        return $dompdf->output();
    }

    protected function save(string $pdfContent, string $path): string
    {
        return $this->storageStrategy->save($pdfContent, $path);
    }
}
