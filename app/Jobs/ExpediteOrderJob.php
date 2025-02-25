<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Models\ClientOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\DeliverOrderJob;

class ExpediteOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order;
    public function __construct(ClientOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->order && $this->order->status === OrderStatus::Placed->value) {

            $this->order->update([
                'status' => OrderStatus::Expedited->value,
                'expedited_at' => now(),
            ]);
    
            // Lansăm job-ul de livrare
            DeliverOrderJob::dispatch($this->order)->delay(now()->addMinutes(1));
        } 
    }
}
