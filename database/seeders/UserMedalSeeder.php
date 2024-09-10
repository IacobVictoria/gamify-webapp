<?php

namespace Database\Seeders;

use App\Models\UserMedal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class UserMedalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        UserMedal::factory(20)->create();
    }
}
