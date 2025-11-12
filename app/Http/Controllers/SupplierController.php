<?php

namespace App\Http\Controllers;

use App\Models\supplier; // <-- FIX: Use correct capitalized 'Supplier'
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $suppliers = supplier::latest()->get();
        return view('supplier.index', compact('suppliers'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('supplier.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers',
            'email' => 'nullable|email|max:255|unique:suppliers',
            'phone' => 'nullable|string|max:20',
            'tax_id' => 'nullable|string|max:50',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        Supplier::create($validatedData);

        return redirect()->route('supplier.index')->with('success', 'Supplier created successfully!');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Supplier $supplier)
    {
        return view('supplier.edit', compact('supplier'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Supplier $supplier)
    {
        $validatedData = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers,name,' . $supplier->id,
            'email' => 'nullable|email|max:255|unique:suppliers,email,' . $supplier->id,
            'phone' => 'nullable|string|max:20',
            'tax_id' => 'nullable|string|max:50',
            'address_line_1' => 'nullable|string|max:255',
            'address_line_2' => 'nullable|string|max:255',
            'city' => 'nullable|string|max:100',
            'state' => 'nullable|string|max:100',
            'postal_code' => 'nullable|string|max:20',
            'country' => 'nullable|string|max:100',
        ]);

        $supplier->update($validatedData);

        return redirect()->route('supplier.index')->with('success', 'Supplier updated successfully!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Supplier $supplier)
    {
        // Add logic here if you need to check for related orders first
        $supplier->delete();
        return redirect()->route('supplier.index')->with('success', 'Supplier deleted successfully!');
    }


    // --------------------------------------------------------------------
    // API METHODS (These were already here)
    // --------------------------------------------------------------------

    public function indexApi()
    {
        return response()->json(Supplier::orderBy('name')->get());
    }

    public function showApi(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'name' => 'required|string|max:255|unique:suppliers',
            'email' => 'nullable|email|unique:suppliers',
        ]);

        $supplier = Supplier::create($validated);

        return response()->json($supplier, 201);
    }
}