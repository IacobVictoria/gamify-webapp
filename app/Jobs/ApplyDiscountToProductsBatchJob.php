<?php

namespace App\Jobs;

use App\Models\Product;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;
use Illuminate\Foundation\Queue\Queueable;

class ApplyDiscountToProductsBatchJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $productIds;
    protected $discountPercentage;
    protected $discountId;

    public function __construct(array $productIds, $discountPercentage, $discountId)
    {
        $this->productIds = $productIds;
        $this->discountPercentage = $discountPercentage;
        $this->discountId = $discountId;
    }

    /**
     * Execute the job.
     */
    public function handle(): void
    {
        $products = Product::whereIn('id', $this->productIds)->get();

        foreach ($products as $product) {
            if (!$product->old_price) {
                $product->old_price = $product->price;
            }
    
            //Obținem reducerile deja aplicate din cache
            $discounts = Cache::get("discount_product_{$product->id}", []);
    
            //Adăugăm noua reducere (evităm duplicarea)
            $exists = collect($discounts)->contains(fn($d) => $d['event_id'] === $this->discountId);
            if (!$exists) {
                $discounts[] = ['event_id' => $this->discountId, 'discount' => $this->discountPercentage];
            }
    
            //Salvăm reducerile actualizate în cache
            Cache::put("discount_product_{$product->id}", $discounts);
    
            //Aplicăm toate reducerile cumulate
            $finalPrice = $product->old_price;
            foreach ($discounts as $discount) {
                $finalPrice *= (1 - $discount['discount'] / 100);
            }
    
            $product->price = round($finalPrice, 2);
            $product->save();
        }
    }
}

