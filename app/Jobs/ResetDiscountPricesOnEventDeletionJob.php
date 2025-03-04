<?php

namespace App\Jobs;

use App\Models\Event;
use App\Models\Product;
use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Support\Facades\Cache;

class ResetDiscountPricesOnEventDeletionJob implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    protected $expiredDiscounts;

    public function __construct($expiredDiscounts)
    {
        $this->expiredDiscounts = $expiredDiscounts;
    }

    public function handle(): void
    {
        foreach ($this->expiredDiscounts as $event) {
            $details = json_decode($event->details, true);

            $products = match ($details['applyTo']) {
                'all' => Product::all(),
                'categories' => Product::where('category', $details['category'])->get(),
                default => collect()
            };

            foreach ($products as $product) {
                $discounts = Cache::get("discount_product_{$product->id}", []);

                // Eliminăm doar discount-ul acestui eveniment
                $discounts = array_filter($discounts, fn($d) => $d['event_id'] !== $event->id);

                if (!empty($discounts)) {
                    // Recalculăm prețul cu reducerile rămase
                    $finalPrice = $product->old_price;
                    foreach ($discounts as $d) {
                        $finalPrice *= (1 - $d['discount'] / 100);
                    }
                    $product->price = round($finalPrice, 2);

                    // Actualizăm cache-ul doar cu reducerile rămase
                    Cache::put("discount_product_{$product->id}", $discounts);
                } else {
                    // Dacă nu mai există reduceri, revenim la prețul inițial
                    $product->price = $product->old_price;
                    $product->old_price = null;
                    Cache::forget("discount_product_{$product->id}");
                }

                $product->save();
            }
        }
    }
}
