<?php

namespace App\Observers;

use App\Models\User;
use App\Services\UserAchievementService;
use Illuminate\Support\Facades\DB;

class UserObserver
{
    protected $achievementService;

    public function __construct(UserAchievementService $achievementService)
    {
        $this->achievementService = $achievementService;
    }

    /**
     * Handle the User "created" event.
     */
    public function created(User $user): void
    {
        $this->updateCSV();
    }

    /**
     * Handle the User "updated" event.
     */
    public function updated(User $user): void
    {
        $this->updateCSV();
        // Check if the score has been updated
        if ($user->isDirty('score')) {
            $newScore = $user->score;
            $this->achievementService->checkAndSendMedalEmail($user, $newScore, $user->score);
        }
    }

    /**
     * Handle the User "deleted" event.
     */
    public function deleted(User $user): void
    {
        $this->updateCSV();
    }

    /**
     * Handle the User "restored" event.
     */
    public function restored(User $user): void
    {
        //
    }

    /**
     * Handle the User "force deleted" event.
     */
    public function forceDeleted(User $user): void
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
