<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use Symfony\Component\Process\Process;

class RunRecommenderPipeline extends Command
{
    protected $signature = 'recommender:run-pipeline';
    protected $description = 'Rulează pipeline-ul complet: preprocesare, inserare, antrenare și generare recomandări';

    public function handle()
    {
        $basePath = base_path('app/recommender_api');

        $this->info('--- Verific update-uri ---');
        $procCheckUpdates = new Process(['python3', "$basePath/check_for_updates.py"]);
        $procCheckUpdates->run();

        $this->info('--- Rulez preprocesare date (users, products) ---');
        $procPreproc = new Process(['python3', "$basePath/main_process_data_train.py"]);
        $procPreproc->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (!$procPreproc->isSuccessful()) {
            $this->error('Eroare la preprocesare date');
            return 1;
        }

        $this->info('--- Inserare date actualizate în DB (insert_to_db_for_updates.py) ---');
        $procInsertDB = new Process(['python3', "$basePath/insert_to_db_for_updates.py"]);
        $procInsertDB->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (!$procInsertDB->isSuccessful()) {
            $this->error('Eroare la inserarea datelor în DB');
            return 1;
        }

        $this->info('--- Pregatesc date echilibrate pentru antrenare (balance_train_data.py) ---');
        $procBalance = new Process(['python3', "$basePath/balance_train_data.py"]);
        $procBalance->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (!$procBalance->isSuccessful()) {
            $this->error('Eroare la echilibrarea datelor.');
            return 1;
        }

        $this->info('--- Reantrenare model (main.py) ---');
        $procTrain = new Process(['python3', "$basePath/main.py"]);
        $procTrain->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (!$procTrain->isSuccessful()) {
            $this->error('Eroare la antrenarea modelului');
            return 1;
        }

        $this->info('--- Generare și salvare recomandări (save_recommandations_to_db.py) ---');
        $procSaveRec = new Process(['python3', "$basePath/save_recommandations_to_db.py"]);
        $procSaveRec->run(function ($type, $buffer) {
            echo $buffer;
        });
        if (!$procSaveRec->isSuccessful()) {
            $this->error('Eroare la generarea/salvarea recomandărilor');
            return 1;
        }

        $this->info('--- Pipeline complet finalizat cu succes! ---');
        return 0;
    }
}
