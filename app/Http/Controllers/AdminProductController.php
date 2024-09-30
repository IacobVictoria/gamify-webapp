<?php

namespace App\Http\Controllers;

use App\Http\Requests\ProductRequest;
use App\Models\Product;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters', []);

        $productsQuery = Product::query();

        if (isset($filters['searchName'])) {
            $productsQuery->where('name', 'like', '%' . $filters['searchName'] . '%');
        }

        if (isset($filters['searchPrice'])) {
            $productsQuery->where('price', 'like', '%' . $filters['searchPrice'] . '%');
        }

        if (isset($filters['searchScore'])) {
            $productsQuery->where('score', 'like', '%' . $filters['searchScore'] . '%');
        }


        $products = $productsQuery->paginate(10)->through(function ($product) {
            return [
                'id' => $product->id,
                'score' => $product->score,
                'name' => $product->name,
                'price' => $product->price,
                'created_at' => $product->created_at->format('Y-m-d'),
            ];
        });

        return Inertia::render('Admin/Products/List', [
            'products' => $products,
            'prevFilters' => $filters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Products/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
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

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $productid)
    {
        $product = Product::find($productid);

        return Inertia::render('Admin/Products/Edit', [
            'product' => $product,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(ProductRequest $request, string $productId)
    {
        $product = Product::findOrFail($productId);
        $validated = $request->validated();

        $product->name = $validated['name'];
        $product->category = $validated['category'];
        $product->description = $validated['description'] ?? '';  
        $product->score = $validated['score'];
        $product->price = $validated['price'];
        $product->stock = $validated['stock'];
    
        $product->save();
    
        return redirect()->route('admin.products.index')->with('success', 'Product updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $productId)
    {
        $product = Product::findOrFail($productId);
        $product->delete();

        return redirect()->route('admin.products.index')
            ->with('success', 'Product deleted successfully!');
    }
}
