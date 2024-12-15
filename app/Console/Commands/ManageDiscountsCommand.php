<?php

namespace App\Console\Commands;

use App\Events\DiscountApplied;
use App\Models\Event;
use App\Models\Product;
use App\Services\NotificationService;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Cache;

class ManageDiscountsCommand extends Command
{
    protected $signature = 'discounts:manage';
    protected $description = 'Manage discounts by updating status and applying rules';
    protected $notificationService;

    public function __construct(NotificationService $notificationService)
    {
        parent::__construct();
        $this->notificationService = $notificationService;
    }

    public function handle()
    {
        $now = now();
        logger('Current time: ' . $now);

        // Obține reducerile care sunt active
        $discounts = Event::where('type', 'discount')
            ->where('status', 'OPEN')
            ->where('start', '<=', $now)
            ->where('end', '>=', $now)
            ->get();

        // Parcurge reducerile active
        foreach ($discounts as $discount) {
            $details = json_decode($discount->details, true);
            logger('Discount details: ' . json_encode($details));

            // Aplică reducerile în funcție de tipul reducerii
            if ($details['applyTo'] === 'all') {
                $this->applyDiscountToAll($details['discount'], $discount->id);
            } elseif ($details['applyTo'] === 'categories') {
                $this->applyDiscountToCategory($details['category'], $details['discount'], $discount->id);
            }
        }

        // Găsește reducerile care au expirat
        $expiredDiscounts = Event::where('type', 'discount')
            ->where('status', 'OPEN')
            ->where('end', '<', $now)
            ->get();

        // Resetează prețurile produselor care au avut reduceri expirate
        $this->resetExpiredDiscountsPrices($expiredDiscounts);

        // Marchează reducerile expirate ca fiind închise
        Event::whereIn('id', $expiredDiscounts->pluck('id')->toArray())
            ->update(['status' => 'CLOSED']);

        // Șterge reducerile expirate din cache
        $this->clearExpiredDiscountsFromCache();

        $this->info('Discounts managed successfully.');
    }

    private function applyDiscountToCategory($category, $discountPercentage, $discountId)
    {
        $userId = Auth::id();
        if (Cache::has("discount_emitted_{$discountId}_user_{$userId}")) {
            $this->info("Event for discount ID {$discountId} already emitted.");
            return;
        }
        Cache::put("discount_emitted_{$discountId}_user_{$userId}", true);

        // Aplică reducerea pentru produsele dintr-o categorie specifică
        $products = Product::where('category', $category)->get();
        foreach ($products as $product) {
            $this->applyDiscount($product, $discountPercentage, $discountId);

            // Salvează reducerea în cache pe produs, folosind product_id
            Cache::put("discount_product_{$product->id}", ['discount' => $discountPercentage]);
        }
        $description = "A discount of {$discountPercentage}% has been applied to all products in the '{$category}' category.";

        broadcast(new DiscountApplied($description, $this->notificationService, Auth::id()));

        $this->info("Applied discount of {$discountPercentage}% to category: {$category}.");
    }

    private function applyDiscountToAll($discountPercentage, $discountId)
    {
        $userId = Auth::id();
        if (Cache::has("discount_emitted_{$discountId}_user_{$userId}")) {
            $this->info("Event for discount ID {$discountId} already emitted.");
            return;
        }
        Cache::put("discount_emitted_{$discountId}_user_{$userId}", true);
        $products = Product::all();
        foreach ($products as $product) {
            $this->applyDiscount($product, $discountPercentage, $discountId);

            // Salvează reducerea în cache pe produs, folosind product_id
            Cache::put("discount_product_{$product->id}", ['discount' => $discountPercentage]);
        }

        $description = "A discount of {$discountPercentage}% has been applied to all products.";
        broadcast(new DiscountApplied($description, $this->notificationService, Auth::id()));
    }

    private function applyDiscount($product, $discountPercentage, $discountId)
    {
        // Dacă old_price nu este setat, îl salvăm
        if (!$product->old_price) {
            $product->old_price = $product->price;
            $product->price = $product->price * (1 - $discountPercentage / 100);

            logger('Product after discount: ' . json_encode($product));

            // Salvăm produsul cu prețul actualizat
            $product->save();
        }


    }

    private function resetExpiredDiscountsPrices($expiredDiscounts)
    {
        $userId = Auth::id();
        foreach ($expiredDiscounts as $discount) {
            // Verificăm reducerile din cache pentru produse
            $products = Product::all();
            Cache::forget("discount_emitted_{$discount->id}_user_{$userId}");
            foreach ($products as $product) {
                // Verifică reducerea din cache pe produs
                $cachedDiscount = Cache::get("discount_product_{$product->id}");

                if ($cachedDiscount) {
                    // Resetăm prețul produsului la cel vechi
                    if ($product->old_price) {
                        $product->price = $product->old_price;
                        $product->old_price = null;
                        $product->save();
                    }

                    // Ștergem reducerea din cache pentru produs
                    Cache::forget("discount_product_{$product->id}");
                }
            }
        }

        $this->info('Expired discount prices reset to original.');
    }

    private function clearExpiredDiscountsFromCache()
    {
        $userId = Auth::id();
        $discountIds = Event::where('type', 'discount')
            ->where('status', 'CLOSED')
            ->pluck('id')
            ->toArray();

        foreach ($discountIds as $discountId) {
            Cache::forget("discount_emitted_{$discountId}_user_{$userId}");
        }

        $this->info('Expired discounts cleared from cache.');
    }
}
