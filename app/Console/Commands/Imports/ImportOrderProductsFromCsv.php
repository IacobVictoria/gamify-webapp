<?php

namespace App\Console\Commands;

use App\Models\OrderProduct;
use Faker\Provider\Uuid;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\DB;
use League\Csv\Reader;

class ImportOrderProductsFromCsv extends Command
{
    protected $signature = 'import:order-products {path}';
    protected $description = 'Importă asocieri order-product dintr-un CSV';
    
    public function handle()
    {
        $path = $this->argument('path');
    
        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();
    
        $bar = $this->output->createProgressBar();
        $bar->start();
    
        foreach ($records as $record) {
            // Verificăm dacă există deja combinația order_id + product_id
            $existing = DB::table('order_products')
                ->where('order_id', $record['order_id'])
                ->where('product_id', $record['product_id'])
                ->first();
    
            if ($existing) {
                // Update
                OrderProduct::where('id', $existing->id)->update([
                    'quantity' => $record['quantity'],
                    'price' => $record['price'],
                ]);
            } else {
                // Insert with ID
                OrderProduct::create([
                    'id' => Uuid::uuid(), // folosește id-ul din CSV
                    'order_id' => $record['order_id'],
                    'product_id' => $record['product_id'],
                    'quantity' => $record['quantity'],
                    'price' => $record['price'],
                ]);
            }
    
            $bar->advance();
        }
    
        $bar->finish();
        $this->info("\n✅ Order-Products importate cu succes.");
    }
}
