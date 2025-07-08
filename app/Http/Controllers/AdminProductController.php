<?php

namespace App\Http\Controllers;

use App\Enums\ProductCategory;
use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Inertia\Inertia;

class AdminProductController extends Controller
{
    public function index(Request $request)
    {
        $filters = $request->input('filters', []);
        $orderBy = $request->input('orderBy', 'created_at');
        $orderDirection = $request->input('orderDirection', 'desc');

        $productsQuery = Product::query();

        if (isset($filters['searchName'])) {
            $productsQuery->where('name', 'like', '%' . $filters['searchName'] . '%');
        }

        if (in_array($orderBy, ['price', 'score', 'created_at'])) {
            $orderDirection = in_array($orderDirection, ['asc', 'desc']) ? $orderDirection : 'asc';
            $productsQuery->orderBy($orderBy, $orderDirection);
        }

        if (isset($filters['searchPublished']) && in_array($filters['searchPublished'], ['true', 'false'])) {
            $productsQuery->where('is_published', $filters['searchPublished'] === 'true');
        }

        $products = $productsQuery->paginate(10)->through(function ($product) {
            return [
                'id' => $product->id,
                'score' => $product->score,
                'name' => $product->name,
                'price' => $product->price,
                'is_published' => $product->is_published,
                'created_at' => $product->created_at->format('Y-m-d'),
            ];
        });

        return Inertia::render('Admin/Products/List', [
            'products' => $products,
            'prevFilters' => $filters
        ]);
    }

    public function create()
    {
        return Inertia::render('Admin/Products/Create');
    }

    public function store(ProductRequest $request)
    {
        $validated = $request->validated();

        $product = new Product();
        $product->id = Uuid::uuid();
        $product->name = $validated['name'];
        $product->category = $validated['category'];
        $product->description = $validated['description'];
        $product->score = $validated['score'];
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
        $product->save();

        return redirect()->route('admin.products.index')->with('success', 'Product created successfully!');
    }

    public function edit(string $productid)
    {
        $product = Product::find($productid);

        $categories = array_map(function ($category) {
            return [
                'value' => $category->value,
                'label' => ucfirst(str_replace('_', ' ', $category->value)),
            ];
        }, ProductCategory::cases());

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
            'categories' => $categories,
        ]);
    }

    public function update(ProductRequest $request, string $productId)
    {
        $product = Product::findOrFail($productId);
        $validated = $request->validated();

        $product->category = $validated['category'];
        $product->description = $validated['description'] ?? '';
        $product->score = $validated['score'];
        $product->price = $validated['price'];
        $product->is_published = $validated['is_published'];
        if ($request->hasFile('image')) {
            // Șterge imaginea veche de pe S3
            if ($product->image_url) {
                // Extrage calea fișierului din URL
                $parsedUrl = parse_url($product->image_url);
                $imagePath = ltrim($parsedUrl['path'], '/');
                Storage::disk('s3')->delete($imagePath);
            }

            // Salvează imaginea noua
            $imagePath = $request->file('image')->store('products_images', 's3');
            $product->image_url = Storage::disk('s3')->url($imagePath);
        }

        $product->save();

        return redirect()->route('admin.products.index')->with('message', 'Produs editat cu succes!');
    }

    public function destroy(string $productId)
    {
        $product = Product::findOrFail($productId);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
