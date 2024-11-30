<?php

namespace App\Http\Controllers;

use App\Models\Product;
use Illuminate\Http\Request;
use Inertia\Inertia;

class ProductComparisonController extends Controller
{
    public function addToComparison(Request $request)
    {
        $product = Product::find($request->input('product_id'));
        $comparison = session('comparison', []);
        if (in_array($product->id, $comparison)) {
            return response()->json(['message' => 'Produsul este deja în lista de comparare.'], 400);
        }
        if (!empty($comparison)) {
            $firstProduct = Product::find($comparison[0]);
            if ($product->category !== $firstProduct->category) {
                session(['comparison' => []]);
                $comparison = [];
            }
        }
        $comparison[] = $product->id;
        session(['comparison' => $comparison]);

        return response()->json(['message' => 'Produs adăugat pentru comparare.']);
    }

    public function removeFromComparison($productId)
    {
        $comparison = session('comparison', []);
        $comparison = array_filter($comparison, fn($id) => $id !== $productId);
        session(['comparison' => $comparison]);

        return response()->json(['message' => 'Produs eliminat din lista de comparare.']);
    }

    public function getProductsComparison()
    {
        $comparison = session('comparison', []);
        $products = Product::whereIn('id', $comparison)->get();

        return response()->json($products);
    }

    // Obține produse comparate dintr-un URL cu ID-uri
    public function getComparisonByIds($ids)
    {
        $productIds = explode(',', $ids);
        $products = Product::whereIn('id', $productIds)->get();

        if ($products->isEmpty()) {
            return response()->json(['message' => 'Nu există produse de comparat.'], 404);
        }

        $comparisonData = $this->compareAttributes($products);

        return Inertia::render('Products/ComparisonPage', [
            'products' => $comparisonData['products'],  
            'recommendations' => $comparisonData['recommendations'],

        ]);
    }
    private function compareAttributes($products)
    {
        $comparison = [];
        $recommendations = [];

        $attributes = ['price', 'calories', 'protein', 'carbs', 'fats', 'fiber', 'sugar'];
        foreach ($attributes as $attribute) {
            $maxValue = $products->max($attribute);
            $minValue = $products->min($attribute);
      
            foreach ($products as $product) {
                if ($product->$attribute == $maxValue) {
                    $comparison[$product->id][$attribute . 'Color'] = 'red';
                  
                } elseif ($product->$attribute == $minValue) {
                    $comparison[$product->id][$attribute . 'Color'] = 'blue';
                } else {
                    $comparison[$product->id][$attribute . 'Color'] = 'neutral'; 
                }
            }
        }

        $allIngredients = [];
        $totalProducts = count($products);

        foreach ($products as $product) {
            $productIngredients = array_map('trim', explode(',', $product->ingredients));
            $allIngredients = array_merge($allIngredients, $productIngredients);
        }

        $ingredientCount = [];
        foreach ($allIngredients as $ingredient) {
            if (!isset($ingredientCount[$ingredient])) {
                $ingredientCount[$ingredient] = 0;
            }
            $ingredientCount[$ingredient]++;
        }

        $commonIngredients = array_keys(array_filter($ingredientCount, function ($count) use ($totalProducts) {
            return $count === $totalProducts;
        }));

        foreach ($products as $product) {
            $productIngredients = array_map('trim', explode(',', $product->ingredients));

            $comparison[$product->id]['commonIngredients'] = $commonIngredients;
            $comparison[$product->id]['nonCommonIngredients'] = array_diff($productIngredients, $commonIngredients);
        }

        // Recomandări pe baza atributelor
        foreach ($attributes as $attribute) {
            $bestProduct = $products->firstWhere($attribute, $products->min($attribute));

            if ($attribute == 'protein') {
                $recommendations[] = "Pentru mai multe proteine, alege {$bestProduct->name}.";
            } elseif ($attribute == 'calories') {
                $recommendations[] = "Pentru mai puține calorii, alege {$bestProduct->name}.";
            } elseif ($attribute == 'sugar') {
                $bestProduct = $products->firstWhere($attribute, $products->min($attribute)); // Mai puțin zahăr
                $recommendations[] = "Pentru mai puțin zahăr, alege {$bestProduct->name}.";
            } elseif ($attribute == 'carbs') {
                $bestProduct = $products->firstWhere($attribute, $products->min($attribute)); // Mai puțini carbohidrați
                $recommendations[] = "Pentru mai puțini carbohidrați, alege {$bestProduct->name}.";
            }
        }

        // Recomandări pentru alergeni
        $productsWithAllergens = $products->filter(function ($product) {
            return !empty($product->allergens);
        });

        if ($productsWithAllergens->count() > 0) {
            $allergenProductNames = $productsWithAllergens->pluck('name')->implode(', ');
            $recommendations[] = "Următoarele produse conțin alergeni: {$allergenProductNames}. Verifică cu atenție alergenii înainte de a alege un produs.";
        } else {
            $recommendations[] = "Niciunul dintre produsele selectate nu conține alergeni.";
        }

        return [
            'products' => $products->map(function ($product) use ($comparison) {
                return array_merge($product->toArray(), [
                    'comparison' => $comparison[$product->id] ?? []
                ]);
            }),
            'recommendations' => $recommendations,
        ];
    }

    public function resetComparison()
    {
        session(['comparison' => []]);
        return response()->json(['message' => 'Lista de comparare a fost resetată.']);
    }
}
