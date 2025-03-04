<?php

namespace App\Console\Commands;


use App\Jobs\ApplyDiscountsJob;
use App\Jobs\ExpireDiscountsJob;
use Illuminate\Console\Command;


class ManageDiscountsCommand extends Command
{
    protected $signature = 'discounts:manage';
    protected $description = 'Manage discounts by updating status and applying rules';

    public function handle()
    {
        dispatch(new ApplyDiscountsJob()); // Aplică reducerile active
        dispatch(new ExpireDiscountsJob()); // Resetează reducerile expirate

        $this->info('Discount jobs dispatched successfully!');
    }

}
