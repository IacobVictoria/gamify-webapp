<?php

namespace App\Console\Commands;

use App\Models\Review;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportReviewsFromCsv extends Command
{
    protected $signature = 'import:reviews {path}';
    protected $description = 'Importă recenzii dintr-un fișier CSV';

    public function handle()
    {
        $path = $this->argument('path');

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $bar = $this->output->createProgressBar();
        $bar->start();

        foreach ($records as $record) {
            Review::updateOrCreate(
                [
                    'id' => $record['id']
                ],
                [
                    'user_id' => $record['user_id'],
                    'product_id' => $record['product_id'],
                    'title' => $record['title'],
                    'rating' => $record['rating'],
                    'description' => $record['description'],
                    'likes' => $record['likes'],
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nRecenzii importate cu succes.");
    }
}
