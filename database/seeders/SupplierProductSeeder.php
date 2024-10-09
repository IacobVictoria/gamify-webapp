<?php

namespace Database\Seeders;

use App\Models\SupplierProduct;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class SupplierProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        SupplierProduct::factory()->count(10)->create();
    }
}
