<?php
namespace App\Http\Controllers;

use App\Models\Unit;
use App\Models\Property;
use Illuminate\Http\Request;

class UnitController extends Controller
{
    public function index()
    {
        // Fetch all units with their associated property
        $units = Unit::with('property')->paginate(10);

        // Return the units to the view
        return view('units.index', compact('units'));
    }

    public function create()
    {
        // Fetch all properties to link with the unit
        $properties = Property::all();
        
        return view('units.create', compact('properties'));
    }

    public function getUnits($property_id)
    {
        // Fetch units for a specific property
        $units = Unit::where('property_id', $property_id)->get();
        
        return response()->json([
            'status' => 'success',
            'units' => $units
        ]);
    }

    public function store(Request $request)
    {
        // Validate the incoming request data
        $request->validate([
            'unit_number' => 'required|string',
            'floor' => 'nullable|integer',
            'rent_amount' => 'required|numeric',
            'status' => 'required|in:Occupied,Vacant',
        ]);

        // Store the unit for the property
        $unit = new Unit($request->all());
        $unit->save();

        return redirect()->route('units.index')->with('success', 'Unit added successfully.');
    }

    public function edit(Unit $unit)
    {
        // Fetch all properties to update the unit's property
        $properties = Property::all();
        return view('units.edit', compact('unit', 'properties'));
    }

    public function update(Request $request, Unit $unit)
    {
        // Validate the incoming request data
        $request->validate([
            'property_id' => 'required|exists:properties,id',
            'unit_number' => 'required|string|max:255',
            'floor' => 'nullable|integer',
            'rent_amount' => 'required|numeric',
            'status' => 'required|in:Occupied,Vacant',
        ]);

        // Update the unit with new data
        $unit->update($request->all());

        return redirect()->route('units.index')->with('success', 'Unit updated successfully.');
    }

    public function destroy(Unit $unit)
    {
        // Delete the unit from the database
        $unit->delete();
        return redirect()->route('units.index')->with('success', 'Unit deleted successfully.');
    }
}
