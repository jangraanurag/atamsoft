<?php

namespace App\Http\Controllers;

use App\Models\Brand;
use App\Models\Buyer;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class BrandController extends Controller
{
    /**
     * Display a listing of the brands.
     */
    public function index()
    {
        $brands = Brand::with('buyer')->latest()->get();
        return view('brand.index', compact('brands'));
    }

    /**
     * Show the form for creating a new brand.
     */
    public function create()
    {
        $buyers = Buyer::all();
        return view('brand.create', compact('buyers'));
    }

    /**
     * Store a newly created brand in storage.
     */
    public function store(Request $request)
    {
        $data = $request->validate([
            'brand_name' => 'required|unique:brands|max:100',
            'brand_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'buyer_id' => 'required|exists:buyers,id',
        ]);

        if ($request->hasFile('brand_image')) {
            $path = $request->file('brand_image')->store('brands', 'public');
            $data['brand_image'] = basename($path);
        }

        Brand::create($data);

        return redirect()->route('brand.index')->with('success', 'Brand created successfully!');
    }

    /**
     * Show the form for editing the specified brand.
     */
    public function edit($id)
    {
        $brand = Brand::findOrFail($id);
        $buyers = Buyer::all();
        return view('brand.edit', compact('brand', 'buyers'));
    }

    /**
     * Update the specified brand in storage.
     */
    public function update(Request $request, $id)
    {
        $brand = Brand::findOrFail($id);

        $data = $request->validate([
            'brand_name' => 'required|max:100|unique:brands,brand_name,' . $brand->id,
            'brand_image' => 'nullable|image|mimes:jpeg,png,jpg,gif,svg|max:2048',
            'buyer_id' => 'required|exists:buyers,id',
        ]);

        if ($request->hasFile('brand_image')) {
            if ($brand->brand_image) {
                Storage::disk('public')->delete('brands/' . $brand->brand_image);
            }
            $path = $request->file('brand_image')->store('brands', 'public');
            $data['brand_image'] = basename($path);
        }

        $brand->update($data);

        return redirect()->route('brand.index')->with('success', 'Brand updated successfully!');
    }

    /**
     * Remove the specified brand from storage.
     */
    public function destroy($id)
    {
        $brand = Brand::findOrFail($id);

        if ($brand->brand_image) {
            Storage::disk('public')->delete('brands/' . $brand->brand_image);
        }

        $brand->delete();

        return redirect()->route('brand.index')->with('success', 'Brand deleted successfully!');
    }

    // --------------------------------------------------------------------
    // API METHODS
    // --------------------------------------------------------------------

    /**
     * Return a listing of all brands for the API.
     * @return \Illuminate\Http\JsonResponse
     */
    public function indexApi()
    {
        return response()->json(Brand::orderBy('brand_name')->get());
    }

    /**
     * Return the specified brand for the API.
     * @param \App\Models\Brand $brand
     * @return \Illuminate\Http\JsonResponse
     */
    public function showApi(Brand $brand)
    {
        return response()->json($brand);
    }

    /**
     * Store a newly created brand from the API.
     * @param \Illuminate\Http\Request $request
     * @return \Illuminate\Http\JsonResponse
     */
    public function storeApi(Request $request)
    {
        $data = $request->validate([
            'brand_name' => 'required|unique:brands|max:100',
            'buyer_id' => 'required|exists:buyers,id',
        ]);

        $brand = Brand::create($data);

        return response()->json($brand, 201);
    }
}
