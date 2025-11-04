<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Product;

class ProductController extends Controller
{
    public function index()
    {
        $products = Product::all();
        return view('products.index', compact('products'));
    }

    public function create()
    {
        return view('products.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'product_name' => 'required|string|max:255|unique:products',
        ]);

        Product::create($request->only('product_name'));

        return redirect()->route('products.index')->with('success', 'Product created successfully.');
    }

    public function edit($id)
    {
        $product = Product::findOrFail($id);


        return view('products.edit', compact('product'));
    }

    public function update($id,Request $request)
    {
        $product = Product::findOrFail($id);
        $request->validate([
            'product_name' => 'required|string|max:255|unique:products,product_name,' . $product->id,
        ]);

        $product->update($request->only('product_name'));

        return redirect()->route('products.index')->with('success', 'Product updated successfully.');
    }

    public function destroy($id)
    {
        $product = Product::findOrFail($id);
        $product->delete();
        return redirect()->route('products.index')->with('success', 'Product deleted successfully.');
    }

    // --------------------------------------------------------------------
    // API METHODS
    // --------------------------------------------------------------------

    public function indexApi()
    {
        return response()->json(Product::orderBy('product_name')->get());
    }

    public function showApi(Product $product)
    {
        return response()->json($product);
    }

    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'product_name' => 'required|string|max:255|unique:products',
        ]);

        $product = Product::create($validated);

        return response()->json($product, 201);
    }
}
