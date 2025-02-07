<?php
namespace App\Http\Controllers;

use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Property;
use Illuminate\Http\Request; // Correct import

class LeaseController extends Controller
{
    // Show form to create a lease
    public function create()
    {
        $tenants = Tenant::all();
        $properties = Property::all();
        return view('leases.create', compact('tenants', 'properties'));
    }

    // Store lease data
    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        Lease::create([
            'tenant_id' => $request->tenant_id,
            'property_id' => $request->property_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('leases.index')->with('success', 'Lease created successfully');
    }

    // Show a list of all leases
 /*   public function index()
    {
        $leases = Lease::all();
        return view('leases.index', compact('leases'));
    }*/
    public function index()
    {
        $leases = Lease::with(['tenant', 'property'])->get(); // Eager load tenant and property
    
        return view('leases.index', compact('leases'));
    }
    

    // Show lease details
    public function show($id)
    {
        $lease = Lease::findOrFail($id);
        return view('leases.show', compact('lease'));
    }

    // Show form to edit lease
    public function edit($id)
    {
        $lease = Lease::findOrFail($id);
        $tenants = Tenant::all();
        $properties = Property::all();
        return view('leases.edit', compact('lease', 'tenants', 'properties'));
    }

    // Update lease data
    public function update(Request $request, $id)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'property_id' => 'required|exists:properties,id',
            'start_date' => 'required|date',
            'end_date' => 'required|date|after:start_date',
        ]);

        $lease = Lease::findOrFail($id);
        $lease->update([
            'tenant_id' => $request->tenant_id,
            'property_id' => $request->property_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
        ]);

        return redirect()->route('leases.index')->with('success', 'Lease updated successfully');
    }

    // Delete lease
    public function destroy($id)
    {
        $lease = Lease::findOrFail($id);
        $lease->delete();

        return redirect()->route('leases.index')->with('success', 'Lease deleted successfully');
    }
}
