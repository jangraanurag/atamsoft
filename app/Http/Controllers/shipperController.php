<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\shipper;

class ShipperController extends Controller
{
    public function index()
    {
        $shippers = Shipper::all();
        return view('shipper.index', compact('shippers'));
    }

    public function create()
    {
        return view('shipper.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'shipper_name' => 'required|string|max:255|unique:shippers',
        ]);

        Shipper::create($request->all());

        return redirect()->route('shipper.index')->with('success', 'Shipper created successfully.');
    }

    public function edit($id)
    {
        $shipper = Shipper::findOrFail($id);
        return view('shipper.edit', compact('shipper'));
    }

    public function update(Request $request, $id)
    {
        $shipper = Shipper::findOrFail($id);
        $request->validate([
            'shipper_name' => 'required|string|max:255|unique:shippers,shipper_name,' . $shipper->id,
        ]);

        $shipper->update($request->all());

        return redirect()->route('shipper.index')->with('success', 'Shipper updated successfully.');
    }

    public function destroy($id)
    {
        $shipper = Shipper::findOrFail($id);
        $shipper->delete();
        return redirect()->route('shipper.index')->with('success', 'Shipper deleted successfully.');
    }


    public function indexApi()
    {
        return response()->json(Shipper::orderBy('shipper_name')->get());
    }

    public function showApi(Shipper $shipper)
    {
        return response()->json($shipper);
    }

    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'shipper_name' => 'required|string|max:255|unique:shippers',
        ]);

        $shipper = Shipper::create($validated);

        return response()->json($shipper, 201);
    }
}
