<?php

namespace App\Jobs;

use App\Enums\OrderStatus;
use App\Events\OrderDeliveredEvent;
use App\Models\ClientOrder;
use App\Models\User;
use App\Services\NotificationService;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class DeliverOrderJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $order, $user;

    /**
     * Create a new job instance.
     */
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
        // Verificăm dacă încă este în starea Expedited
        if ($this->order->status === OrderStatus::Expedited->value) {
            $this->order->update([
                'status' => OrderStatus::Delivered,
                'delivered_at' => now(),
            ]);

            $notificationService = app(NotificationService::class);
            SendWhatsappMessageJob::dispatch( 'order_delivered', ['name' => $this->order->user->name,'order_id'=>$this->order->id]);

            broadcast(new OrderDeliveredEvent($this->user, $this->order, $notificationService));
            // Lansăm job pentru arhivare după 1 zi
            ArchiveOrderJob::dispatch($this->order)->delay(now()->addDay());
        }

    }
}
