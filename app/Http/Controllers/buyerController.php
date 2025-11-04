<?php

namespace App\Http\Controllers;

use App\Models\Buyer;
use Illuminate\Http\Request;

class BuyerController extends Controller
{
    /**
     * Display a listing of the buyers for the web interface.
     */
    public function index()
    {
        $buyers = Buyer::with('brands')->latest()->get();
        return view('buyer.index', compact('buyers'));
    }

    /**
     * Show the form for creating a new buyer.
     */
    public function create()
    {
        return view('buyer.create');
    }

    /**
     * Store a newly created buyer in storage for the web interface.
     */
    public function store(Request $request)
    {
        $validatedData = $request->validate([
            'buyer_name' => 'required|unique:buyers|max:50',
            'buyer_address' => 'nullable|string|max:255',
            'buyer_state' => 'nullable|string|max:255',
            'buyer_country' => 'nullable|string|max:255',
        ]);

        Buyer::create($validatedData);

        return redirect()->route('buyer.index')->with('success', 'Buyer created successfully!');
    }

    /**
     * Show the form for editing the specified buyer.
     */
    public function edit($id)
    {
        $buyer = Buyer::findOrFail($id);
        return view('buyer.edit', compact('buyer'));
    }

    /**
     * Update the specified buyer in storage for the web interface.
     */
    public function update(Request $request, $id)
    {
        $validatedData = $request->validate([
            'buyer_name' => 'required|max:50|unique:buyers,buyer_name,' . $id,
            'buyer_address' => 'nullable|string|max:255',
            'buyer_state' => 'nullable|string|max:255',
            'buyer_country' => 'nullable|string|max:255',
        ]);

        $buyer = Buyer::findOrFail($id);
        $buyer->update($validatedData);

        return redirect()->route('buyer.index')->with('success', 'Buyer updated successfully!');
    }

    /**
     * Remove the specified buyer from storage.
     */
    public function destroy($id)
    {
        $buyer = Buyer::findOrFail($id);
        $buyer->delete();

        return redirect()->route('buyer.index')->with('success', 'Buyer deleted successfully!');
    }

    // --------------------------------------------------------------------
    // API METHODS
    // --------------------------------------------------------------------

    /**
     * Return a listing of all buyers for the API.
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexApi()
    {
        return response()->json(Buyer::orderBy('buyer_name')->get());
    }

    /**
     * Return the specified buyer for the API.
     * @param \App\Models\Buyer $buyer
     * @return \Illuminate\Http\JsonResponse
     */
    public function showApi(Buyer $buyer)
    {
        return response()->json($buyer);
    }

    /**
     * Store a newly created buyer from the API.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    /**
     * Store a newly created buyer from the API.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeApi(Request $request)
    {
        $validatedData = $request->validate([
            'buyer_name' => 'required|unique:buyers,buyer_name|max:50',
            'buyer_address' => 'nullable|string',
            'buyer_city' => 'nullable|string|max:255',
            'buyer_state' => 'nullable|string|max:255',
            'buyer_country' => 'nullable|string|max:255',
            'buyer_mobile_no' => 'nullable|string|max:20',
            'buyer_email_id' => 'nullable|email|max:255',
            'buyer_tax_no' => 'nullable|string|max:255',
        ]);

        // 1. Create the new buyer
        $newBuyer = Buyer::create($validatedData);

        // 2. Get the fresh list of ALL buyers
        $allBuyers = Buyer::orderBy('buyer_name')->get();

        // 3. Return the exact structure the JavaScript needs
        return response()->json([
            'message'      => 'Buyer created successfully!',
            'buyers'       => $allBuyers,
            'new_buyer_id' => $newBuyer->id,
        ], 201); // 201 = Created
    }
}
