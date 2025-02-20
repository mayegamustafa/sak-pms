<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PropertiesExport;
use Mpdf\Mpdf;

use App\Models\Unit;
use App\Models\User;


use Mpdf\Output\Destination;


class PropertyController extends Controller
{

    public function index(Request $request)
{
    // Temporarily remove the owner_id filter to see all properties.
    $query = Property::with('manager'); //->where('owner_id', Auth::id());

    // Search filter
    if ($request->filled('search')) {
        $search = $request->search;
        $query->where(function($q) use ($search) {
            $q->where('name', 'LIKE', "%{$search}%")
              ->orWhere('location', 'LIKE', "%{$search}%");
        });
    }

    // Remove price filtering if the column doesn't exist:
    // if ($request->filled('min_price') && $request->filled('max_price')) {
    //     $query->whereBetween('price_per_unit', [$request->min_price, $request->max_price]);
    // }

    $properties = $query->orderBy($request->get('sort', 'name'), 'asc')->paginate(5);

    // Get overall stats based on the properties in the paginated result
    $propertyCount = $properties->total();
    $propertyIds = $properties->pluck('id');
    $totalUnits = \App\Models\Unit::whereIn('property_id', $propertyIds)->count();
    $averageRent = \App\Models\Unit::whereIn('property_id', $propertyIds)->avg('rent_amount');

    // For debugging: dump the results to check if data exists.
    // dd($properties);

    return view('properties.index', compact('properties', 'propertyCount', 'totalUnits', 'averageRent'));
}

   /* public function index(Request $request)
    {
        $properties = Property::with('manager')->get();
         

        $query = Property::where('owner_id', Auth::id());
    
        if ($request->has('search')) {
            $query->where('name', 'LIKE', "%{$request->search}%")
                  ->orWhere('location', 'LIKE', "%{$request->search}%");
        }
    
        if ($request->has('min_price') && $request->has('max_price')) {
            $query->whereBetween('price_per_unit', [$request->min_price, $request->max_price]);
        }
    
        $properties = $query->orderBy($request->get('sort', 'name'), 'asc')->paginate(5);
        $propertyCount = $query->count();
    
        // Fix: Fetch total units from the 'units' table instead of 'properties'
        $totalUnits = \App\Models\Unit::whereIn('property_id', $query->pluck('id'))->count();
    
        // If you want the average rent across all units
        $averageRent = \App\Models\Unit::whereIn('property_id', $query->pluck('id'))->avg('rent_amount');
    
        return view('properties.index', compact('properties', 'propertyCount', 'totalUnits', 'averageRent'));
    }
    
*/
/*
public function create()
{
    // Fetch only users with the role of "Manager"
    $managers = User::where('role', 'manager')->get();
    $property = new Property; // Create a new empty property object
    return view('properties.create', compact('property','managers'));
} */
public function create()
    {
        // Get only users with role 'manager'
       // $users = User::all();
       // $managers = User::where('role', 'manager')->get();
       // return view('properties.create', compact( 'users'));
       $owners = User::where('role', 'Owner')->get();
$managers = User::where('role', 'Manager')->get();
return view('properties.create', compact('owners', 'managers'));

    }
     /**
     * Store a newly created property in storage.
     */
    public function store(Request $request)
    {
        // Validate the input data
        $request->validate([
            'name' => 'required|string',
            'location' => 'required|string',  // Add validation for location
            'type' => 'required|in:House,Flat',  // Validating type
            'num_units' => 'required|integer',
            'num_floors' => 'nullable|integer',
            'owner_id' => 'required|exists:users,id',
            'manager_id' => 'nullable|exists:users,id',
        ]);
    
        // Save the property first
        $property = Property::create([
            'name' => $request->name,
            'location' => $request->location,
            'type' => $request->type,
            'num_units' => $request->num_units,
            'num_floors' => $request->num_floors,
            'owner_id' => $request->owner_id,
            'manager_id' => $request->manager_id,
        ]);
    
        // Generate unit codes and save units if the property is of type 'Flat'
        if ($property->type == 'Flat') {
            $numFloors = $property->num_floors;
            $unitsPerFloor = ceil($property->num_units / $numFloors);
    
            for ($floor = 1; $floor <= $numFloors; $floor++) {
                for ($i = 1; $i <= $unitsPerFloor; $i++) {
                    $unit_code = "L" . $floor . str_pad($i, 3, '0', STR_PAD_LEFT);
                    Unit::create([
                        'property_id' => $property->id,
                        'unit_number' => $unit_code,
                        'floor' => $floor,
                        'rent_amount' => 0,  // You can set the rent amount to a default value here
                        'status' => 'Vacant',
                    ]);
                }
            }
        }
    
        // Return the saved property as a JSON response
        return response()->json([
            'status' => 'success',
            'property' => $property,
        ], 200);
    }
    
    /**
     * Generate units for a House property.
     */
    private function generateHouseUnits(Property $property)
    {
        $prefix = strtoupper(substr($property->name, 0, 1)); // First letter as prefix
    
        for ($i = 1; $i <= $property->num_units; $i++) {
            $unit_code = $prefix . str_pad($i, 2, '0', STR_PAD_LEFT);
            Unit::create([
                'property_id' => $property->id,
                'unit_number' => $unit_code,
                'floor'       => null, // Houses do not have floors
                'rent_amount' => 0, // Default rent
                'status'      => 'Vacant',
            ]);
        }
    }
    
    /**
     * Generate units for a Flat property.
     */
    private function generateFlatUnits(Property $property, array $floorsUnits)
    {
        if (!empty($floorsUnits)) {
            // Custom floor-wise distribution
            foreach ($floorsUnits as $floor => $unitCount) {
                for ($i = 1; $i <= $unitCount; $i++) {
                    $unit_code = "L" . $floor . str_pad($i, 3, '0', STR_PAD_LEFT);
                    Unit::create([
                        'property_id' => $property->id,
                        'unit_number' => $unit_code,
                        'floor'       => $floor,
                        'rent_amount' => 0,
                        'status'      => 'Vacant',
                    ]);
                }
            }
        } else {
            // Equal distribution among floors if no specific input is given
            $numFloors = $property->num_floors;
            $unitsPerFloor = intdiv($property->num_units, $numFloors);
    
            for ($floor = 1; $floor <= $numFloors; $floor++) {
                for ($i = 1; $i <= $unitsPerFloor; $i++) {
                    $unit_code = "L" . $floor . str_pad($i, 3, '0', STR_PAD_LEFT);
                    Unit::create([
                        'property_id' => $property->id,
                        'unit_number' => $unit_code,
                        'floor'       => $floor,
                        'rent_amount' => 0,
                        'status'      => 'Vacant',
                    ]);
                }
            }
        }
    }
    
 /*   
public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string',
        'location' => 'required|string',  // Add validation for location
        'type' => 'required|in:House,Flat',
        'num_units' => 'required|integer',
        'num_floors' => 'nullable|integer',
        'owner_id' => 'required|exists:users,id',
        'manager_id' => 'nullable|exists:users,id',
    ]);
    
    // Save the property first
    $property = Property::create([
        'name' => $request->name,
        'location' => $request->location, // Ensure location is saved
        'type' => $request->type,
        'num_units' => $request->num_units,
        'num_floors' => $request->num_floors,
        'owner_id' => Auth::id(),
        'manager_id' => $request->manager_id,
    ]);

    // Generate Unit Codes
$prefix = strtoupper(substr($request->name, 0, 1)); // First letter of property name

for ($i = 1; $i <= $request->num_units; $i++) {
    if ($request->type == 'Flat') {
        // If it's a flat, create units with floors
        if ($request->num_floors) {
            for ($floor = 1; $floor <= $request->num_floors; $floor++) {
                $unitCode = "{$prefix}{$floor}00{$i}"; // Example: B1001 (Bunga Level 1)
                Unit::create([
                    'property_id' => $property->id, // Correctly assign property_id
                    'unit_code' => $unitCode,
                    'floor_number' => $floor,
                ]);
            }
        } else {
            // If there are no floors, create a single unit code for flat type
            $unitCode = "{$prefix}" . str_pad($i, 2, '0', STR_PAD_LEFT);
            Unit::create([
                'property_id' => $property->id,
                'unit_code' => $unitCode,
            ]);
        }
    } else {
        // For houses, create a unit code without floors
        $unitCode = "{$prefix}" . str_pad($i, 2, '0', STR_PAD_LEFT); // Example: H01, H02
        Unit::create([
            'property_id' => $property->id,
            'unit_code' => $unitCode,
        ]);
    }
}
*/

//dd($property);  // This will dump the saved property

   // return redirect()->route('properties.index')->with('success', 'Property added successfully!');
//}

public function update(Request $request, Property $property)
{
    if ($property->owner_id !== Auth::id()) {
        abort(403, 'Unauthorized action.');
    }

    $request->validate([
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'units' => 'required|integer|min:1',
    ]);

    $property->update($request->only(['name', 'location', 'units']));

    return redirect()->route('properties.index')->with('success', 'Property updated successfully!');
}


    public function edit(Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('properties.edit', compact('property'));
    }


    public function destroy(Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $property->delete();
        return redirect()->route('properties.index')->with('success', 'Property deleted successfully!');
    }

   
    public function exportExcel()
    {
        return Excel::download(new PropertiesExport, 'properties.xlsx');
    }
    
    public function exportPdf()
{
    $properties = Property::all();

    // Load view as HTML
    $html = view('properties.pdf', compact('properties'))->render();

    try {
        // Initialize mPDF
        $mpdf = new Mpdf();

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output the PDF for download
        return response()->streamDownload(function () use ($mpdf) {
            echo $mpdf->Output('', Destination::STRING_RETURN);
        }, 'properties.pdf');

    } catch (\Exception $e) {
        return response()->json(['error' => $e->getMessage()], 500);
    }
}


public function generateReport()
{
    $totalProperties = Property::count();
    $totalUnits = Property::sum('units');
    $averageRent = Property::avg('price_per_unit');
    
    return view('properties.report', compact('totalProperties', 'totalUnits', 'averageRent'));
}


// In PropertyController
public function showPerformanceChart()
{
    $properties = Property::all();
    $labels = $properties->pluck('name');
    $data = $properties->pluck('units');

    return view('properties.chart', compact('labels', 'data'));
}

public function report()
{
    return view('properties.report'); // Ensure you have a 'properties/report.blade.php' file
}

public function performanceChart() {
    return view('properties.performance-chart');
}

public function export()
    {
        return Excel::download(new PropertiesExport, 'properties.xlsx');
        
    }

    public function show($id)
    {
        // Fetch the property by ID
        $property = Property::findOrFail($id);
    
        // Return a response (view, JSON, etc.)
        return view('properties.show', compact('property'));
    }
    

}
