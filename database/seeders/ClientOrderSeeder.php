<?php

namespace Database\Seeders;

use App\Models\ClientOrder;
use App\Models\ClientOrderModel;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class ClientOrderSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        ClientOrder::factory(20)->create();
    }
}
