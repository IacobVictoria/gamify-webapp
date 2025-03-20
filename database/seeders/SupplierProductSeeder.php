<?php

namespace Database\Seeders;

use App\Models\Supplier;
use App\Models\SupplierProduct;
use Faker\Provider\Uuid;
use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;

class SupplierProductSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        $products = [
            ['name' => 'Protein Bar - Chocolate', 'category' => 'Vitamins'],
            ['name' => 'Protein Bar - Peanut Butter', 'category' => 'Vitamins'],
            ['name' => 'Protein Cookie - Double Chocolate', 'category' => 'Vitamins'],
            ['name' => 'Protein Cookie - Oatmeal Raisin', 'category' => 'Vitamins'],
            ['name' => 'Whey Protein - Vanilla', 'category' => 'Supplements'],
            ['name' => 'Whey Protein - Chocolate', 'category' => 'Supplements'],
            ['name' => 'Vegan Protein - Pea & Rice', 'category' => 'Supplements'],
            ['name' => 'Creatine Monohydrate', 'category' => 'Supplements'],
            ['name' => 'BCAA - Lemon Lime', 'category' => 'Supplements'],
            ['name' => 'BCAA - Watermelon', 'category' => 'Supplements'],
            ['name' => 'Electrolyte Powder - Orange', 'category' => 'Vitamins'],
            ['name' => 'Energy Drink - Berry Blast', 'category' => 'Vitamins'],
            ['name' => 'Energy Drink - Tropical Mix', 'category' => 'Vitamins'],
            ['name' => 'Oatmeal - Apple Cinnamon', 'category' => 'Vitamins'],
            ['name' => 'Oatmeal - Maple Brown Sugar', 'category' => 'Vitamins'],
        ];
        foreach ($products as $product) {
            $randomId = strtoupper(Str::random(5)); 
            $categoryCode = strtoupper(substr($product['category'], 0, 3)); 
            $nameCode = strtoupper(substr($product['name'], 0, 4)); 

            $productSku = "{$nameCode}-{$categoryCode}-{$randomId}"; 
            SupplierProduct::create([
                'id' => Uuid::uuid(),
                'name' => $product['name'],
                'category' => $product['category'],
                'description' => 'A delicious and nutritious ' . strtolower($product['name']) . '.',
                'product_sku' => $productSku,
                'price' => rand(10, 100),
                'stock' => rand(5, 50),
                'calories' => rand(100, 500),
                'protein' => rand(5, 30),
                'carbs' => rand(10, 50),
                'fats' => rand(2, 15),
                'fiber' => rand(1, 10),
                'sugar' => rand(1, 15),
                'ingredients' => 'Protein blend, sweetener, cocoa, oats, natural flavors',
                'allergens' => 'May contain traces of nuts, dairy, soy',
                'supplier_id' => Supplier::inRandomOrder()->first()->id, 
            ]);
        }
    }
}
