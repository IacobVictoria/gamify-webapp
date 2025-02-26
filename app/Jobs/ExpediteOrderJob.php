<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Events\OrderExpeditedEvent;
use App\Models\ClientOrder;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use App\Jobs\DeliverOrderJob;

class ExpediteOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public $order, $user;
    public function __construct(ClientOrder $order, User $user)
    {
        $this->order = $order;
        $this->user = $user;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if ($this->order && $this->order->status === OrderStatus::Authorized->value) {

            $this->order->update([
                'status' => OrderStatus::Expedited->value,
                'expedited_at' => now(),
            ]);

            // Instanțiem NotificationService direct în metodă, nu merge in constructor
            $notificationService = app(NotificationService::class);

            broadcast(new OrderExpeditedEvent($this->user, $this->order, $notificationService));
            // Lansăm job-ul de livrare
            DeliverOrderJob::dispatch($this->order, $this->user)->delay(now()->addMinutes(1));
        }
    }
}
