<?php

namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        $units = Unit::with('property')->paginate(10);
        return view('units.index', compact('units'));
    }

    public function create()
    {
        $properties = Property::all();
        return view('units.create', compact('properties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:255',
            'floor' => 'nullable|integer',
            'rent_amount' => 'required|numeric',
            'status' => 'required|in:Occupied,Vacant',
        ]);

        Unit::create($request->all());
        return redirect()->route('units.index')->with('success', 'Unit added successfully.');
    }

    public function edit(Unit $unit)
    {
        $properties = Property::all();
        return view('units.edit', compact('unit', 'properties'));
    }

    public function update(Request $request, Unit $unit)
    {
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:255',
            'floor' => 'nullable|integer',
            'rent_amount' => 'required|numeric',
            'status' => 'required|in:Occupied,Vacant',
        ]);

        $unit->update($request->all());
        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
