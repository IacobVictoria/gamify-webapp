<?php

namespace App\Console\Commands;

use App\Jobs\ProcessEventSupplierOrdersJob;
use Illuminate\Console\Command;
use App\Interfaces\SupplierOrderHandlerInterface;

class ProcessSupplierOrders extends Command
{
    protected $signature = 'orders:process-supplier-orders';

    protected $description = 'Verifică evenimentele de tip supplier_order din calendar și plasează comenzile corespunzătoare';

    public function handle()
    {
        $handler = app(SupplierOrderHandlerInterface::class);

        ProcessEventSupplierOrdersJob::dispatch($handler);
    }
}
