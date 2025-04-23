<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;

class GeneratePublicTokens extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'app:generate-public-tokens';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Command description';

    public function handle()
    {
        $updated = 0;
    
        \App\Models\User::whereNull('public_token')->chunkById(100, function ($users) use (&$updated) {
            foreach ($users as $user) {
                $user->public_token = (string) \Str::uuid();
                $user->save();
                $updated++;
            }
        });
    
        $this->info("Token-uri generate pentru $updated useri.");
    }
    
}
