<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;
use Symfony\Component\Process\Exception\ProcessFailedException;

class RunRecommenderPipeline extends Command
{
    protected $signature = 'recommender:run';
    protected $description = 'Rulare pipeline recomandări dacă sunt modificări în date';

    public function handle()
    {
        //verificare sunt schimbari in bd
        $check = new Process(['python', 'app/recommender_api/check_for_updates.py']);
        $check->run();

        if (!$check->isSuccessful()) {
            throw new ProcessFailedException($check);
        }

        $changed = trim($check->getOutput()) === '1';

        if (!$changed) {
            $this->info('Datele nu s-au schimbat. Nu rulez pipeline-ul.');
            return;
        }

        $this->info('Datele s-au schimbat. Pornesc pipeline-ul...');

        $steps = [
            'process_users.py',
            'process_products.py',
            'prepare_training_data_positive.py',
            'prepare_training_data_negative.py',
            'combine_train.py',
            'main.py',
        ];

        foreach ($steps as $step) {
            $this->info("Rulez $step");
            $process = new Process(['python', "app/recommender_api/{$step}"]);
            $process->setTimeout(3600); // 1 oră maxim

            $process->run(function ($type, $buffer) {
                echo $buffer;
            });

            if (!$process->isSuccessful()) {
                throw new ProcessFailedException($process);
            }
        }

        $this->info('Pipeline-ul a fost rulat cu succes!');
    }
}
