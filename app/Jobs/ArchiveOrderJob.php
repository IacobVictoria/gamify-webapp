<?php

namespace App\Jobs;

use App\Models\ClientOrder;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ArchiveOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order;

    public function __construct(ClientOrder $order)
    {
        $this->order = $order;
    }


    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->order && $this->order->status === 'Delivered') {

            $this->order->update([
                'is_archived' => true, // Marchez comanda ca arhivată
            ]);
        }
    }
}
