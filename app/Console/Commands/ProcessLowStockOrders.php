<?php

namespace App\Console\Commands;

use App\Interfaces\SupplierLowStockOrderHandlerInterface;
use App\Jobs\ProcessLowStockOrdersJob;
use App\Services\OrderProcessingService;
use Faker\Provider\Uuid;
use Illuminate\Console\Command;

class ProcessLowStockOrders extends Command
{
    protected $signature = 'orders:process-low-stock';
    protected $description = 'Verifică stocurile și generează comenzi pentru produsele cu stoc scăzut';
    protected SupplierLowStockOrderHandlerInterface $handler;

    public function __construct(SupplierLowStockOrderHandlerInterface $handler)
    {
        parent::__construct();
        $this->handler = $handler;
    }
    public function handle()
    {
        dispatch(new ProcessLowStockOrdersJob($this->handler));
        $this->info('Job-ul de procesare a comenzilor pentru stocuri scăzute a fost lansat.');
    }

}
