<?php

namespace App\Console\Commands;

use App\Models\Product;
use Illuminate\Console\Command;
use League\Csv\Reader;
use Faker\Factory as Faker;

class ImportProductsFromCsv extends Command
{
    protected $signature = 'import:products {path}';
    protected $description = 'Import products from a CSV file';

    public function handle()
    {
        $path = $this->argument('path');

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();
        $faker = Faker::create();
        $bar = $this->output->createProgressBar();
        $bar->start();

        foreach ($records as $record) {
            Product::updateOrCreate(
                ['id' => $record['id']], // identificare unică
                [
                    'name' => $record['name'],
                    'category' => $record['category'],
                    'description' => $record['description'],
                    'price' => $record['price'],
                    'stock' => $record['stock'],
                    'score' => $record['score'],
                    'calories' => $record['calories'],
                    'protein' => $record['protein'],
                    'carbs' => $record['carbs'],
                    'fats' => $record['fats'],
                    'fiber' => $record['fiber'],
                    'sugar' => $record['sugar'],
                    'ingredients' => $record['ingredients'],
                    'allergens' => $record['allergens'],
                    'old_price' => $record['old_price'] !== '' ? $record['old_price'] : null,
                    'image_url' => $record['image_url'],
                    'product_sku' => null,
                    'is_published' => strtolower($record['is_published']) === 'true' ? 1 : 0,
                    'slug' => $record['slug'],
                ]
            );

            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✅ Produsele au fost importate cu succes.");
    }
}
