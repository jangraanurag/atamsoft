<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\d_unit;

class DUnitController extends Controller
{
    public function index()
    {
        $dunits = D_unit::all();
        return view('dunit.index', compact('dunits'));
    }

    public function create()
    {
        return view('dunit.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'dunit_name' => 'required|unique:d_units|max:20',
            'dunit_shorthand' => 'required|unique:d_units|max:10',
        ]);

        D_unit::create($request->only(['dunit_name', 'dunit_shorthand']));

        return redirect()->route('dunit.index')->with('success', 'Dimension Unit created successfully!');
    }

    public function edit(D_unit $d_unit)
    {
        return view('dunit.edit', compact('d_unit'));
    }

    public function update(Request $request, D_unit $d_unit)
    {
        $request->validate([
            'dunit_name' => 'required|max:20|unique:d_units,dunit_name,' . $d_unit->id,
            'dunit_shorthand' => 'required|max:10|unique:d_units,dunit_shorthand,' . $d_unit->id,
        ]);

        $d_unit->update($request->only(['dunit_name', 'dunit_shorthand']));

        return redirect()->route('dunit.index')->with('success', 'Dimension Unit updated successfully!');
    }

    public function destroy(D_unit $d_unit)
    {
        $d_unit->delete();
        return redirect()->route('dunit.index')->with('success', 'Dimension Unit deleted successfully!');
    }

    // --------------------------------------------------------------------
    // API METHODS
    // --------------------------------------------------------------------

    public function indexApi()
    {
        return response()->json(D_unit::orderBy('dunit_name')->get());
    }

    public function showApi(D_unit $d_unit)
    {
        return response()->json($d_unit);
    }

    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'dunit_name' => 'required|string|max:20|unique:d_units',
            'dunit_shorthand' => 'required|string|max:10|unique:d_units',
        ]);

        $dunit = D_unit::create($validated);

        return response()->json($dunit, 201);
    }
}
