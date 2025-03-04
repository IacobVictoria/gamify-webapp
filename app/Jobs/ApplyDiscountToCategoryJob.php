<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ApplyDiscountToCategoryJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $category;
    protected $discountPercentage;
    protected $discountId;

    public function __construct($category, $discountPercentage, $discountId)
    {
        $this->category = $category;
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

        // Procesăm produsele din categoria selectată în loturi de 100
        Product::where('category', $this->category)->chunk(100, function ($products) {
            $productIds = $products->pluck('id')->toArray();
            dispatch(new ApplyDiscountToProductsBatchJob($productIds, $this->discountPercentage, $this->discountId));
        });
        dispatch(new BroadcastDiscountAppliedJob("A discount of {$this->discountPercentage}% at category {$this->category}."));
    }
}
