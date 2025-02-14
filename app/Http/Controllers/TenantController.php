<?php


namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Services\SmsService;


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
        $units = Unit::all();
        return view('tenants.create', compact('properties','units'));
    }

public function sendSmsToTenant($tenantId, SmsService $smsService)
{
    $tenant = Tenant::findOrFail($tenantId);

    if (!$tenant->phone_number) {
        return redirect()->back()->with('error', 'Tenant has no phone number.');
    }

    $message = "Hello {$tenant->name}, this is a test SMS from our property system.";

    $smsService->sendSms($tenant->phone_number, $message);

    return redirect()->back()->with('success', 'SMS sent successfully.');
}


 /*   public function getUnits($property_id)
{
    $units = \App\Models\Unit::where('property_id', $property_id)->get();
    return response()->json($units);
}
*/


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
        // Start a transaction to ensure both the tenant and unit are saved correctly
        DB::beginTransaction();
        
        try {
            // Create a new tenant
            $tenant = Tenant::create([
                'name' => $request->name,
                'property_id' => $request->property_id,
                'unit_id' => $request->unit_id,
                'phone_number' => $request->phone_number,
                'lease_start_date' => $request->lease_start_date,
                'rent_amount' => $request->rent_amount,
            ]);

            // Update the selected unit to "Occupied"
            $unit = Unit::findOrFail($request->unit_id);
            $unit->status = 'Occupied';  // Mark the unit as occupied
            $unit->save();

            // Commit the transaction
            DB::commit();

            return redirect()->route('tenants.index')->with('success', 'Tenant added successfully');
        } catch (\Exception $e) {
            // Rollback the transaction if anything goes wrong
            DB::rollBack();

            return back()->with('error', 'An error occurred while adding the tenant');
        }
    }
 /*   public function store(Request $request)
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
       // 'email' => 'nullable|email', // Add validation if needed
        'email' => 'nullable|email|unique:tenants,email',
        'phone_number' => 'nullable|string|min:10|max:15',
    ]);

    // Create the tenant using the request data
    Tenant::create($requestData);

    return redirect()->route('tenants.index')->with('success', 'Tenant added successfully!');
}  */


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
           // 'rent_amount' => 'required|numeric',
            'email' => 'nullable|email|unique:tenants,email,' . $tenant->id,
        'phone_number' => 'nullable|string|min:10|max:15',
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
