<?php

namespace App\Jobs;

use App\Interfaces\SupplierLowStockOrderHandlerInterface;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;


class ProcessLowStockOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;
    protected SupplierLowStockOrderHandlerInterface $handler;

    public function __construct(SupplierLowStockOrderHandlerInterface $handler)
    {
        $this->handler = $handler;
    }

    public function handle()
    {
        $this->handler->handle();
    }
}
