<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\LabelType;

class LabelTypeController extends Controller
{
    public function index()
    {
        $labeltypes = LabelType::all();
        return view('labeltype.index', compact('labeltypes'));
    }

    public function create()
    {
        return view('labeltype.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'labeltype_name' => 'required|unique:label_types|max:50',
        ]);

        LabelType::create($request->only('labeltype_name'));

        return redirect()->route('labeltype.index')->with('success', 'Label Type created successfully!');
    }

    public function edit($id)
    {
        $labelType = LabelType::findOrFail($id);
        return view('labeltype.edit', compact('labelType'));
    }

    public function update($id,Request $request)
    {
        $labelType = LabelType::findOrFail($id);
        $request->validate([
            'labeltype_name' => 'required|max:50|unique:label_types,labeltype_name,' . $labelType->id,
        ]);

        $labelType->update($request->only('labeltype_name'));

        return redirect()->route('labeltype.index')->with('success', 'Label Type updated successfully!');
    }

    public function destroy($id)
    {
        $labelType = LabelType::findOrFail($id);



        $labelType->delete();
        return redirect()->route('labeltype.index')->with('success', 'Label Type deleted successfully!');
    }

    // --------------------------------------------------------------------
    // API METHODS
    // --------------------------------------------------------------------

    public function indexApi()
    {
        return response()->json(LabelType::orderBy('labeltype_name')->get());
    }

    public function showApi(LabelType $label_type)
    {
        return response()->json($label_type);
    }

    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'labeltype_name' => 'required|string|max:50|unique:label_types',
        ]);

        $labelType = LabelType::create($validated);

        return response()->json($labelType, 201);
    }
}
