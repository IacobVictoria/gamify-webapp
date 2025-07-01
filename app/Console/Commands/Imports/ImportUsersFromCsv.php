<?php

namespace App\Console\Commands;

use App\Models\User;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Hash;
use League\Csv\Reader;

class ImportUsersFromCsv extends Command
{

    protected $signature = 'import:users {path}';
    protected $description = 'Import users from a CSV file';

    public function handle()
    {
        $path = $this->argument('path');

        if (!file_exists($path)) {
            $this->error("Fișierul nu există: $path");
            return;
        }

        $csv = Reader::createFromPath($path, 'r');
        $csv->setHeaderOffset(0);
        $records = $csv->getRecords();

        $bar = $this->output->createProgressBar();
        $bar->start();

        foreach ($records as $record) {
            User::updateOrCreate(
                ['email' => $record['email']], // cheia unică
                [
                    'name' => $record['name'],
                    'email' => $record['email'],
                    'gender' => ucfirst(strtolower($record['gender'])),
                    'score' => $record['score'],
                    'used_discounts' => null,
                    'password' => Hash::make('password'), // default password
                ]
            );
            $bar->advance();
        }

        $bar->finish();
        $this->info("\nUtilizatorii au fost importați cu succes.");
    }
}
