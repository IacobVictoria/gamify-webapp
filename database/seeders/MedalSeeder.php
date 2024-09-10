<?php

namespace Database\Seeders;

use App\Enums\MedalTier;
use App\Models\Medal;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Provider\Uuid;

class MedalSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        foreach (MedalTier::cases() as $tier) {
            Medal::create([
                'id' =>  Uuid::uuid(),  
                'tier' => $tier->value,  
            ]);
        }
    }
}
