<?php

namespace App\Console\Commands;

use App\Models\RecommendationData;
use Illuminate\Console\Command;

class ImportRecommendations extends Command
{
    protected $signature = 'import:recommendations {file}';
    protected $description = 'Importa recomandari dintr-un CSV';
    public function handle()
    {
       $file = $this->argument('file');
        if (!file_exists($file)) {
            $this->error("Fisierul $file nu exista.");
            return 1;
        }

        $data = array_map('str_getcsv', file($file));
        $header = array_shift($data);

        foreach ($data as $row) {
            $rowAssoc = array_combine($header, $row);
            RecommendationData::create($rowAssoc);
        }

        $this->info('Import finalizat cu succes.');
        return 0;
    }
}
