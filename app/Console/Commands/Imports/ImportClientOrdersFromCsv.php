<?php

namespace App\Console\Commands;

use App\Models\ClientOrder;
use Illuminate\Console\Command;
use League\Csv\Reader;
use Faker\Factory as Faker;

class ImportClientOrdersFromCsv extends Command
{
    protected $signature = 'import:client-orders {path}';
    protected $description = 'Import client orders from a CSV file';

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
            ClientOrder::updateOrCreate(
                ['id' => $record['order_id']],
                [
                    'user_id' => $record['user_id'],
                    'placed_at' => $record['order_date'],
                    'expedited_at' => null,
                    'delivered_at' => null,
                    'is_archived' => 0,
                    'invoice_url' => null,
                    'promo_code' => null,
                    'discount_amount' => null,
                    'report_id' => null,
                     'total_price' => $faker->randomFloat(2, 10, 300),
                     'email' => $faker->safeEmail(),
                     'first_name' => $faker->firstName(),
                     'last_name' => $faker->lastName(),
                     'address' => $faker->streetAddress(),
                     'apartment' => $faker->randomElement([null, $faker->buildingNumber()]),
                     'state' => $faker->state(),
                     'city' => $faker->city(),
                     'country' => $faker->country(),
                     'zip_code' => $faker->postcode(),
                     'phone' => $faker->phoneNumber(),
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->info("\n✅ Comenzile au fost importate cu succes.");
    }
}
