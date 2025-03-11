<?php

namespace App\Jobs;

use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Services\OrderProcessingService;
use Illuminate\Support\Facades\Log;


class ProcessLowStockOrdersJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public OrderProcessingService $orderProcessingService;

    public function __construct(OrderProcessingService $orderProcessingService)
    {
        $this->orderProcessingService = $orderProcessingService;
    }

    public function handle()
    {     Log::info("a intrat in job!");
        $this->orderProcessingService->processLowStockProducts();
    }
}
