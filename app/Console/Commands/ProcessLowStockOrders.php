<?php

namespace App\Console\Commands;

use App\Jobs\ProcessLowStockOrdersJob;
use App\Services\OrderProcessingService;
use Faker\Provider\Uuid;
use Illuminate\Console\Command;

class ProcessLowStockOrders extends Command
{
    protected $signature = 'orders:process-low-stock';
    protected $description = 'Verifică stocurile și generează comenzi pentru produsele cu stoc scăzut';
    protected $orderProcessingService;
    public function __construct(OrderProcessingService $orderProcessingService)
    {
        parent::__construct();
        $this->orderProcessingService = $orderProcessingService;
    }
    public function handle()
    {
        dispatch(new ProcessLowStockOrdersJob($this->orderProcessingService));
        $this->info('Job-ul de procesare a comenzilor pentru stocuri scăzute a fost lansat.');
    }

}
