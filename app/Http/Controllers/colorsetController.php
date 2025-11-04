<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Colorset; // Assuming you need to use the colorset model

class colorsetController extends Controller
{
    public function index()
    {
        $colorsets = colorset::all();
        return view('colorset.index', compact('colorsets'));
    }

    public function create()
    {
        return view('colorset.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'colorset_name' => 'required|unique:colorsets|max:255',
        ]);

        $colorset = new colorset();
        $colorset->colorset_name = $request->colorset_name;
        $colorset->save();

        return redirect()->route('colorset.index')->with('success', 'Colorset created successfully!');
    }

    public function edit($id)
    {
        $colorset = colorset::findOrFail($id);
        return view('colorset.edit', compact('colorset'));
    }

    public function update(Request $request, $id)
    {
        $request->validate([
            'colorset_name' => 'required|unique:colorsets,colorset_name,' . $id . '|max:255',
        ]);

        $colorset = colorset::findOrFail($id);
        $colorset->colorset_name = $request->colorset_name;
        $colorset->save();

        return redirect()->route('colorset.index')->with('success', 'Colorset updated successfully!');
    }

    public function destroy($id)
    {
        $colorset = colorset::findOrFail($id);
        $colorset->delete();

        return redirect()->route('colorset.index')->with('success', 'Colorset deleted successfully!');
    }

}
