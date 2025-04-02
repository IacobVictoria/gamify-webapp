<?php

namespace App\Http\Controllers;

use App\Http\Requests\SupplierRequest;
use App\Models\Supplier;
use Faker\Provider\Uuid;
use Illuminate\Http\Request;
use Inertia\Inertia;

class AdminSupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        $filters = $request->input('filters', []);

        $suppliersQuery = Supplier::query();

        if (isset($filters['searchName'])) {
            $suppliersQuery->where('name', 'like', '%' . $filters['searchName'] . '%');
        }

        if (isset($filters['searchEmail'])) {
            $suppliersQuery->where('email', 'like', '%' . $filters['searchEmail'] . '%');
        }

        $suppliers = $suppliersQuery->paginate(10)->through(function ($supplier) {
            return [
                'id' => $supplier->id,
                'email' => $supplier->email,
                'name' => $supplier->name,
                'created_at' => $supplier->created_at->format('Y-m-d'),
            ];
        });

        return Inertia::render('Admin/Suppliers/List', [
            'suppliers' => $suppliers,
            'prevFilters' => $filters
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return Inertia::render('Admin/Suppliers/Create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(SupplierRequest $request)
    {

        $validated = $request->validated();

        $supplier = new Supplier();
        $supplier->id = Uuid::uuid();
        $supplier->name = $validated['name'];
        $supplier->email = $validated['email'];
        $supplier->phone = $validated['phone'];

        $supplier->save();

        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier created successfully!');
    }


    public function edit(string $supplierId)
    {
        $supplier = Supplier::find($supplierId);

        return Inertia::render('Admin/Suppliers/Edit', [
            'supplier' => $supplier,
        ]);
    }

    public function update(SupplierRequest $request, string $supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $validated = $request->validated();

        $supplier->name = $validated['name'];
        $supplier->email = $validated['email'];
        $supplier->phone = $validated['phone']; 
    
        $supplier->save();
    
        return redirect()->route('admin.suppliers.index')->with('success', 'Supplier updated successfully!');
        
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $supplierId)
    {
        $supplier = Supplier::findOrFail($supplierId);
        $supplier->delete();

        return redirect()->route('admin.suppliers.index')
            ->with('success', 'Supplier deleted successfully!');
    }
}
