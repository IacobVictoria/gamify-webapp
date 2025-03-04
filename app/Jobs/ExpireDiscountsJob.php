<?php

namespace App\Jobs;

use App\Models\Event;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ExpireDiscountsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = now();
        $expiredDiscounts = Event::where('type', 'discount')
            ->where('status', 'OPEN')
            ->where('end', '<', $now)
            ->get();

        if ($expiredDiscounts->isNotEmpty()) {
            dispatch(new ResetDiscountPricesOnEventDeletionJob($expiredDiscounts));
            dispatch(new UpdateExpiredDiscountStatusesJob($expiredDiscounts));
            dispatch(new ClearExpiredDiscountsFromCacheJob());
        }
    }
}
