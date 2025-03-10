<?php

namespace App\Jobs;

use App\Services\WhatsappService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class SendWhatsappMessageJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected string $messageType;
    protected array $data;

    /**
     * Create a new job instance.
     */
    public function __construct( string $messageType, array $data = [])
    {
        $this->messageType = $messageType;
        $this->data = $data;
    }

    /**
     * Execute the job.
     */
    public function handle(WhatsappService $whatsappService)
    {
        $whatsappService->sendMessage("40727142462", $this->messageType, $this->data);

    }
}
