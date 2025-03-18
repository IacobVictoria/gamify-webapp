<?php

namespace App\Jobs;

use App\Models\ClientOrder;
use App\Services\QrCodes\QrCodeService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
class GenerateProductQrCodesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected ClientOrder $order;

    /**
     * Create a new job instance.
     */
    public function __construct(ClientOrder $order)
    {
        $this->order = $order;
    }
   
    public function handle(QrCodeService $qrCodeService): void
    {
        foreach ($this->order->products as $orderProduct) {
            for ($i = 0; $i < $orderProduct->pivot->quantity; $i++) {
                $qrCodeService->generateQrForProduct($orderProduct->id);
            }
        }
    }
}
