<?php

namespace App\Jobs;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class UpdateExpiredDiscountStatusesJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $expiredDiscounts;

    public function __construct($expiredDiscounts)
    {
        $this->expiredDiscounts = $expiredDiscounts;
    }

    public function handle(): void
    {
        Event::whereIn('id', $this->expiredDiscounts->pluck('id'))->update(['status' => 'CLOSED']);
    }
}
