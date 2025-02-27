<?php

namespace App\Http\Controllers;

use App\Models\Supplier;
use App\Models\SupplierProduct;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminSupplierProductController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {

        $searchQuery = $request->input('search', '');

        $products = SupplierProduct::where('name', 'like', "%{$searchQuery}%")->with('supplier')->get();

        return Inertia::render('Admin/Supplier_Products/List', [
            'products' => $products,
            'searchQueryProp' => $searchQuery
        ]);
    }
    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(string $supplierId)
    {
        $products = SupplierProduct::where('supplier_id', $supplierId)->get();

        $supplier = Supplier::findOrFail($supplierId);

        return Inertia::render('Admin/Supplier_Products/Show', [
            'products' => $products,
            'supplier' => $supplier
        ]);
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
