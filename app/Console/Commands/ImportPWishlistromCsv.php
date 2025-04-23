<?php

namespace App\Console\Commands;

use App\Models\Wishlist;
use Illuminate\Console\Command;
use League\Csv\Reader;

class ImportPWishlistromCsv extends Command
{
    protected $signature = 'import:wishlists {path}';
    protected $description = 'Import wishlists from a CSV file';

    public function handle()
    {
        $path = $this->argument('path');

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0); // prima linie are headerele
        $records = $csv->getRecords();

        $bar = $this->output->createProgressBar();
        $bar->start();

        foreach ($records as $record) {
            Wishlist::updateOrCreate(
                [
                    'user_id' => $record['user_id'],
                    'product_id' => $record['product_id'],
                ],
                [
                    'id' => $record['id'] ?? null, // opțional, dacă vrei să păstrezi ID-ul
                ]
            );
            
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✅ Wishlist-uri importate cu succes.");
    }
}
