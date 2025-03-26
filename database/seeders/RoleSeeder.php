<?php

namespace Database\Seeders;

use App\Models\Role;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Provider\Uuid;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $roles = [
            'admin-gamification',
            'super-admin',
            'admin',
            'user',
        ];

        foreach ($roles as $role) {
            Role::query()->firstOrCreate([
                'id' => Uuid::uuid(),
                'name' => $role,
            ]);
        }
    }
}
