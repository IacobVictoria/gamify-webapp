<?php

namespace App\Observers;

use App\Models\Product;
use Illuminate\Support\Facades\DB;

class ProductObserver
{
    /**
     * Handle the Product "created" event.
     */
    public function created(Product $product): void
    {
        $this->updateCSV();
    }

    /**
     * Handle the Product "updated" event.
     */
    public function updated(Product $product): void
    {
        $this->updateCSV();
    }

    /**
     * Handle the Product "deleted" event.
     */
    public function deleted(Product $product): void
    {
        $this->updateCSV();
    }

    /**
     * Handle the Product "restored" event.
     */
    public function restored(Product $product): void
    {
        //
    }

    /**
     * Handle the Product "force deleted" event.
     */
    public function forceDeleted(Product $product): void
    {
        //
    }

    protected function updateCSV()
    {
        $data = DB::table('client_orders')
            ->join('order_products', 'client_orders.id', '=', 'order_products.order_id')
            ->join('products', 'order_products.product_id', '=', 'products.id')
            ->join('users', 'client_orders.user_id', '=', 'users.id')
            ->select(
                'client_orders.user_id',
                'users.name as user_name',
                'products.id as product_id',
                'products.category as product_category',
                'order_products.price',
                'products.calories',
                'products.protein',
                'products.ingredients',
                'products.allergens',
                'client_orders.created_at as order_timestamp'
            )
            ->get();

        if ($data->isEmpty()) {
            return;
        }

        $filePath = storage_path('app/user_orders.csv');
        $fileHandle = fopen($filePath, 'w');

        fputcsv($fileHandle, [
            'user_id',
            'user_name',
            'product_id',
            'product_category',
            'price',
            'calories',
            'protein',
            'ingredients',
            'allergens',
            'order_timestamp'
        ]);

        foreach ($data as $row) {
            fputcsv($fileHandle, (array) $row);
        }

        fclose($fileHandle);
    }
}
