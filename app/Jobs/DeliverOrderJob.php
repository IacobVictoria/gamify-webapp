<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Models\ClientOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeliverOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    /**
     * Create a new job instance.
     */
    public function __construct(ClientOrder $order)
    {
        $this->order = $order;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        // Verificăm dacă încă este în starea Expedited
        if ($this->order->status === OrderStatus::Expedited->value) {
            $this->order->update([
                'status' => OrderStatus::Delivered,
                'delivered_at' => now(),
            ]);
        }
        // Lansăm job pentru arhivare după 1 zi
        ArchiveOrderJob::dispatch($this->order)->delay(now()->addMinutes(1));
    }
}
