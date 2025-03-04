<?php

namespace App\Jobs;
use App\Models\Event;
use App\Services\NotificationService;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldBeUnique;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;

class ApplyDiscountsJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    public function __construct()
    {
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $now = now();
        $discounts = Event::where('type', 'discount')
            ->where('status', 'OPEN')
            ->where('start', '<=', $now)
            ->where('end', '>=', $now)
            ->where('is_published', true)
            ->get();

        foreach ($discounts as $discount) {
            $details = json_decode($discount->details, true);
            match ($details['applyTo']) {
                'all' => dispatch(new ApplyDiscountToAllJob($details['discount'], $discount->id)),
                'categories' => dispatch(new ApplyDiscountToCategoryJob($details['category'], $details['discount'], $discount->id)),
                default => null
            };
        }
    }
}
