<?php

namespace Database\Seeders;

use App\Models\Permission;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Faker\Provider\Uuid;

class PermissionSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $permissions = [
            'create-user',
            'read-user',
            'update-user',
            'delete-user',
            'create-role',
            'read-role',
            'update-role',
            'delete-role',
            'create-permission',
            'read-permission',
            'update-permission',
            'delete-permission',
        ];

        foreach ($permissions as $permission) {
            Permission::create([
                'id' => Uuid::uuid(),
                'permission' => $permission,
            ]);
        }
    }
}
