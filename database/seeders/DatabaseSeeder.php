<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        $this->call([
          // MedalSeeder::class,
           // UserSeeder::class,
           // UserMedalSeeder::class,
          // ClientOrderSeeder::class,
          //ProductSeeder::class,
         //OrderProductSeeder::class
        // RoleSeeder::class,
        //PermissionSeeder::class
    //    SupplierSeeder::class
        ]);       

    }
}
