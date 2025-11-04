<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\unit;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::all();
        return view('unit.index', compact('units'));
    }

    public function create()
    {
        return view('unit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'unit_name' => 'required|unique:units|max:20',
            'unit_shorthand' => 'required|unique:units|max:10',
        ]);

        Unit::create($request->only(['unit_name', 'unit_shorthand']));

        return redirect()->route('unit.index')->with('success', 'Unit created successfully!');
    }

    public function edit(Unit $unit)
    {
        return view('unit.edit', compact('unit'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'unit_name' => 'required|max:20|unique:units,unit_name,' . $unit->id,
            'unit_shorthand' => 'required|max:10|unique:units,unit_shorthand,' . $unit->id,
        ]);

        $unit->update($request->only(['unit_name', 'unit_shorthand']));

        return redirect()->route('unit.index')->with('success', 'Unit updated successfully!');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('unit.index')->with('success', 'Unit deleted successfully!');
    }

    // --------------------------------------------------------------------
    // API METHODS
    // --------------------------------------------------------------------

    public function indexApi()
    {
        return response()->json(Unit::orderBy('unit_name')->get());
    }

    public function showApi(Unit $unit)
    {
        return response()->json($unit);
    }

    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'unit_name' => 'required|string|max:20|unique:units',
            'unit_shorthand' => 'required|string|max:10|unique:units',
        ]);

        $unit = Unit::create($validated);

        return response()->json($unit, 201);
    }
}
