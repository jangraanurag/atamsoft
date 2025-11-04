<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\status;

class StatusController extends Controller
{
    public function index()
    {
        $statuses = Status::all();
        return view('status.index', compact('statuses'));
    }

    public function create()
    {
        return view('status.create');
    }

    public function store(Request $request)
    {
        $request->validate([
            'status_name' => 'required|unique:statuses|max:255',
        ]);

        Status::create($request->only('status_name'));

        return redirect()->route('status.index')->with('success', 'Status created successfully!');
    }

    public function edit($id)
    {
        $status = Status::findOrFail($id);
        return view('status.edit', compact('status'));
    }

    public function update(Request $request, $id)
    {
        $status = Status::findOrFail($id);

        $request->validate([
            'status_name' => 'required|unique:statuses,status_name,' . $status->id . '|max:255',
        ]);

        $status->update($request->only('status_name'));

        return redirect()->route('status.index')->with('success', 'Status updated successfully!');
    }

    public function destroy($id)
    {
        $status = Status::findOrFail($id);

        $status->delete();
        return redirect()->route('status.index')->with('success', 'Status deleted successfully!');
    }

    // --------------------------------------------------------------------
    // API METHODS
    // --------------------------------------------------------------------

    public function indexApi()
    {
        return response()->json(Status::orderBy('status_name')->get());
    }

    public function showApi(Status $status)
    {
        return response()->json($status);
    }

    public function storeApi(Request $request)
    {
        $validated = $request->validate([
            'status_name' => 'required|string|max:255|unique:statuses',
        ]);

        $status = Status::create($validated);

        return response()->json($status, 201);
    }
}
