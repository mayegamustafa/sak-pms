<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Property;
use Illuminate\Support\Facades\Auth;
use Barryvdh\DomPDF\Facade as PDF;
use Maatwebsite\Excel\Facades\Excel;
use App\Exports\PropertiesExport;
use Mpdf\Mpdf;

use Mpdf\Output\Destination;


class PropertyController extends Controller
{
   /* public function index()
    {
        $properties = Property::where('owner_id', Auth::id())->get();
        return view('properties.index', compact('properties'));
    } */
    public function index(Request $request)
    {
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
    
  /*  public function index(Request $request)
{
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
$totalUnits = $query->sum('units');
$averageRent = $query->avg('price_per_unit');

return view('properties.index', compact('properties', 'propertyCount', 'totalUnits', 'averageRent'));


   // return view('properties.index', compact('properties'));
}  */

public function create()
{
    $property = new Property; // Create a new empty property object
    return view('properties.create', compact('property'));
}

public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'location' => 'required|string|max:255',
        'units' => 'required|integer|min:1',
        'type' => 'required|string',  // Ensure 'type' is validated
        
    ]);

    Property::create([
        'name' => $request->name,
        'location' => $request->location,
        'units' => $request->units,
        'owner_id' => Auth::id(),
        'type' => $request->type,  // Store the selected 'type'
    ]);

    return redirect()->route('properties.index')->with('success', 'Property added successfully!');
}

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

 /*   public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'units' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
        ]);

        Property::create([
            'name' => $request->name,
            'location' => $request->location,
            'units' => $request->units,
            'price_per_unit' => $request->price_per_unit,
            'owner_id' => Auth::id(),
        ]);

        return redirect()->route('properties.index')->with('success', 'Property added successfully!');
    }

    */

    public function edit(Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }
        return view('properties.edit', compact('property'));
    }

 /*   public function edit($id)
{
    $property = Property::findOrFail($id);
    return view('properties.edit', compact('property'));
}
*/

   /* public function update(Request $request, Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $request->validate([
            'name' => 'required|string|max:255',
            'location' => 'required|string|max:255',
            'units' => 'required|integer|min:1',
            'price_per_unit' => 'required|numeric|min:0',
        ]);

        $property->update($request->all());

        return redirect()->route('properties.index')->with('success', 'Property updated successfully!');
    } */

    public function destroy(Property $property)
    {
        if ($property->owner_id !== Auth::id()) {
            abort(403, 'Unauthorized action.');
        }

        $property->delete();
        return redirect()->route('properties.index')->with('success', 'Property deleted successfully!');
    }

    // In your PropertyController
   /* public function exportPdf()
    {
        $properties = Property::all();

        // Load the HTML view
        $html = view('properties.pdf', compact('properties'))->render();

        // Initialize mPDF
        $mpdf = new Mpdf();

        // Write HTML to PDF
        $mpdf->WriteHTML($html);

        // Output as a downloadable file
        return response()->streamDownload(
            fn() => print($mpdf->Output('', 'S')),
            'properties.pdf'
        );
    } */
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

/*
public function exportPdf()
{
    $properties = Property::all();
   // $pdf = PDF::loadView('properties.pdf', compact('properties'));
   // return $pdf->download('properties.pdf');

    $pdf = PDF::loadView('properties.pdf', ['properties' => Property::all()]);
    return $pdf->download('properties.pdf');
}
*/
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

   /* public function exportPdf()
    {
        $properties = Property::all();
        $pdf = Pdf::loadView('properties.pdf', compact('properties'));
        return $pdf->download('properties.pdf');
    }
    */

    public function show($id)
    {
        // Fetch the property by ID
        $property = Property::findOrFail($id);
    
        // Return a response (view, JSON, etc.)
        return view('properties.show', compact('property'));
    }
    

}
