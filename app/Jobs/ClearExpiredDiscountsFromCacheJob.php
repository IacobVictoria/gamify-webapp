<?php

namespace App\Jobs;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ClearExpiredDiscountsFromCacheJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function handle(): void
    {
        $expiredDiscounts = Event::where('type', 'discount')
            ->where('status', 'CLOSED')
            ->pluck('id') 
            ->toArray();

        foreach ($expiredDiscounts as $discountId) {
            Cache::forget("discount_emitted_{$discountId}");
        }
    }
}
