<?php

namespace Database\Seeders;

use App\Models\ReportCategory;
use Faker\Provider\Uuid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ReportCategorySeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $categories = [
            [
                'id' => Uuid::uuid(),
                'name' => 'participants'
            ],
            [
                'id' => Uuid::uuid(),
                'name' => 'supplier_invoice'
            ],
            [
                'id' => Uuid::uuid(),
                'name' => 'client_invoice'
            ],
            [
                'id' => Uuid::uuid(),
                'name' => 'nps_report'
            ],
            [
                'id' => Uuid::uuid(),
                'name' => 'user_activity_monthly'
            ],
            [
                'id' => Uuid::uuid(),
                'name' => 'sales_stock_monthly'
            ],
            [
                'id' => Uuid::uuid(),
                'name' => 'games_activity_monthly'
            ],
            [
                'id' => Uuid::uuid(),
                'name' => 'products_activity_monthly'
            ],
            [
                'id' => Uuid::uuid(),
                'name' => 'rewards_activity_monthly'
            ]
        ];

        foreach ($categories as $category) {
            ReportCategory::create($category);
        }
    }
}
