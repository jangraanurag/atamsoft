<?php

namespace App\Http\Controllers;

use App\Models\supplier;
use Illuminate\Http\Request;

class SupplierController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        // Add web view logic if needed in the future
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Add web view logic if needed in the future
    }

    // ... other web methods

    // --------------------------------------------------------------------
    // API METHODS
    // --------------------------------------------------------------------

    /**
     * Return a listing of all suppliers for the API.
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexApi()
    {
        return response()->json(Supplier::orderBy('name')->get());
    }

    /**
     * Return the specified supplier for the API.
     * @param \App\Models\Supplier $supplier
     * @return \Illuminate\Http\JsonResponse
     */
    public function showApi(Supplier $supplier)
    {
        return response()->json($supplier);
    }

    /**
     * Store a newly created supplier from the API.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
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
