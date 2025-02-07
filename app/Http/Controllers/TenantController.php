<?php


namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;

class TenantController extends Controller
{
    // Display all tenants
    public function index()
    {
        $tenants = Tenant::with('property', 'unit')->paginate(10);
        return view('tenants.index', compact('tenants'));
    }

    // Show form to create a new tenant
    public function create()
    {
        $properties = Property::all();
        return view('tenants.create', compact('properties'));
    }

    // Store a new tenant in the database
   /* public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:units,id',
            'lease_start_date' => 'required|date',
            'rent_amount' => 'required|numeric',
        ]);

        Tenant::create($request->all());

        return redirect()->route('tenants.index')->with('success', 'Tenant added successfully!');
    }  */

    public function store(Request $request)
{
    // Exclude _token from the request before passing it to the model
    $requestData = $request->except('_token');
    
    // Validate the incoming request
    $request->validate([
        'name' => 'required|string|max:255',
        'property_id' => 'required|exists:properties,id',
        'unit_id' => 'nullable|exists:units,id',
        'lease_start_date' => 'required|date',
        'rent_amount' => 'required|numeric',
        'email' => 'nullable|email', // Add validation if needed
    ]);

    // Create the tenant using the request data
    Tenant::create($requestData);

    return redirect()->route('tenants.index')->with('success', 'Tenant added successfully!');
}


    // Display a single tenant
    public function show(Tenant $tenant)
    {
        return view('tenants.show', compact('tenant'));
    }

    // Show form to edit tenant details
    public function edit(Tenant $tenant)
    {
        $properties = Property::all();
        $units = Unit::where('property_id', $tenant->property_id)->get();
        return view('tenants.edit', compact('tenant', 'properties', 'units'));
    }

    // Update tenant details in the database
    public function update(Request $request, Tenant $tenant)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:units,id',
            'lease_start_date' => 'required|date',
            'rent_amount' => 'required|numeric',
        ]);

        $tenant->update($request->all());

        return redirect()->route('tenants.index')->with('success', 'Tenant updated successfully!');
    }

    // Delete a tenant
    public function destroy(Tenant $tenant)
    {
        $tenant->delete();
        return redirect()->route('tenants.index')->with('success', 'Tenant deleted successfully!');
    }
}
