<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Foundation\Queue\Queueable;
use Illuminate\Support\Facades\Cache;

class ApplyDiscountToAllJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $discountPercentage;
    protected $discountId;

    public function __construct($discountPercentage, $discountId)
    {
        $this->discountPercentage = $discountPercentage;
        $this->discountId = $discountId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        if (Cache::has("discount_emitted_{$this->discountId}")) {
            return;
        }
        Cache::put("discount_emitted_{$this->discountId}", true);

        // Procesăm produsele în loturi (100 per lot)
        Product::chunk(100, function ($products) {
            $productIds = $products->pluck('id')->toArray();
            dispatch(new ApplyDiscountToProductsBatchJob($productIds, $this->discountPercentage, $this->discountId));
        });
        dispatch(new BroadcastDiscountAppliedJob("A discount of {$this->discountPercentage}% has been applied to all products."));
    }
}
