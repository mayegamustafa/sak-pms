<?php


namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;
use App\Services\SmsService;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;



class TenantController extends Controller
{
    protected $smsService;
    //protected $smsService;
    protected $twilio;

    // Display all tenants
    public function index()
{
    $tenants = Tenant::with('property', 'unit')->paginate(10);

    $activeTenants = Tenant::where('is_active', true)->get();
    $pastTenants = Tenant::where('is_active', false)->get();
    $vacantUnits = Unit::where('status', 'Vacant')->get();

    return view('tenants.index', compact('activeTenants', 'pastTenants', 'vacantUnits','tenants'));
}


    // Show form to create a new tenant
    public function create()
    {
        $properties = Property::all();
        $units = Unit::all();
        return view('tenants.create', compact('properties','units'));
    }
    /*

public function sendSmsToTenant($tenantId, SmsService $smsService)
{
    $tenant = Tenant::findOrFail($tenantId);

    if (!$tenant->phone_number) {
        return redirect()->back()->with('error', 'Tenant has no phone number.');
    }

    $message = "Hello {$tenant->name}, this is a test SMS from our property system.";

    $smsService->sendSms($tenant->phone_number, $message);

    return redirect()->back()->with('success', 'SMS sent successfully.');
} */public function sendSmsToTenant($tenantId)
{
    $tenant = Tenant::find($tenantId);

    if (!$tenant) {
        return response()->json(['error' => 'Tenant not found'], 404);
    }

    if (empty($tenant->phone_number)) {
        return response()->json(['error' => 'Tenant phone number is missing'], 400);
    }

    // Calculate months since lease started
    $leaseStart = \Carbon\Carbon::parse($tenant->lease_start_date);
    $now = \Carbon\Carbon::now();
    $monthsDue = $leaseStart->diffInMonths($now);

    // Calculate total rent due
    $totalRentDue = $tenant->rent_amount * $monthsDue;

    // Get total payments made (assuming a payments table)
    $totalPayments = \DB::table('payments')
        ->where('tenant_id', $tenant->id)
        ->sum('amount_paid');

    // Calculate balance
    $balance = $totalRentDue - $totalPayments - $tenant->security_deposit;

    // Prepare SMS message
    $message = "Hello {$tenant->name}, your balance is UGX " . number_format($balance) . ". Your lease started on: {$tenant->lease_start_date}. Please clear it before your lease ends on: {$tenant->lease_end_date}. Thank you!";

    try {
        $this->twilio->messages->create($tenant->phone_number, [
            'from' => env('TWILIO_FROM'),
            'body' => $message
        ]);
        return redirect()->back()->with('success', 'SMS sent successfully.');
    } catch (\Exception $e) {
        return redirect()->back()->with(['error' => 'SMS failed: ' . $e->getMessage()], 500);
    }
}

/*
public function sendSmsToTenant($tenantId)
{
    $tenant = Tenant::find($tenantId);

    if (!$tenant) {
        return response()->json(['error' => 'Tenant not found'], 404);
    }

    if (empty($tenant->phone_number)) {
        return response()->json(['error' => 'Tenant phone number is missing'], 400);
    }

    $message = "Hello {$tenant->name}, your balance is UGX {$tenant->balance}. Reason: {$tenant->reason}. Please clear it. Thank you!";

    try {
        $this->twilio->messages->create($tenant->phone_number, [
            'from' => env('TWILIO_FROM'),
            'body' => $message
        ]);

        return response()->json(['success' => 'SMS sent successfully']);
    } catch (\Exception $e) {
        return response()->json(['error' => 'SMS failed: ' . $e->getMessage()], 500);
    }
} */


public function __construct(SmsService $smsService)
    {
        $this->smsService = $smsService;
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendBulkSms()
    {
        $response = $this->smsService->sendBulkSms();
        return redirect()->back()->with('success', $response);
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
 /*   public function store(Request $request)
    {
        // Start a transaction to ensure both the tenant and unit are saved correctly
        DB::beginTransaction();
        
        try {
            // Create a new tenant
            $tenant = Tenant::create([
                'name' => $request->name,
                'property_id' => $request->property_id,
                'email' => $request->email,
                'unit_id' => $request->unit_id,
                'phone_number' => $request->phone_number,
                'lease_start_date' => $request->lease_start_date,
                'lease_end_date' => $request->lease_end_date,
                'security_deposit' => $request->security_deposit,
                'rent_amount' => $request->rent_amount,
               'is_active' => $request->is_active,
               
             
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
    */public function store(Request $request)
{
    $request->validate([
        'name' => 'required|string|max:255',
        'property_id' => 'required|exists:properties,id',
        'unit_id' => 'required|exists:units,id',
        'lease_start_date' => 'required|date',
        'months_paid' => 'required|integer|min:1', // Number of months the tenant is paying for
    ]);

    // Fetch unit and rent amount
    $unit = Unit::findOrFail($request->unit_id);
    $rentAmount = $unit->rent_amount; // Assuming unit has a 'rent_amount' column

    // Calculate lease end date
    $startDate = \Carbon\Carbon::parse($request->lease_start_date);
    $endDate = $startDate->addMonths($request->months_paid);

    // Create Tenant Record
    $tenant = Tenant::create([
        'name' => $request->name,
        'property_id' => $request->property_id,
        'unit_id' => $request->unit_id,
        'lease_start_date' => $request->lease_start_date,
        'lease_end_date' => $endDate, // Automatically set
        'rent_amount' => $rentAmount,
        'months_paid' => $request->months_paid, // Store months paid for reference
    ]);

    return redirect()->route('tenants.index')->with('success', 'Tenant added successfully!');
}

  /*  public function store(Request $request)
    {
        // Validate incoming request
        $request->validate([
            'name' => 'required|string|max:255',
            'property_id' => 'required|exists:properties,id',
            'unit_id' => 'nullable|exists:units,id',
            'phone_number' => 'required|string|max:15',
            'lease_start_date' => 'required|date',
            'rent_amount' => 'required|numeric',
        ]);
    
        // Start a database transaction
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
    
            // If a unit is assigned, update its status to "Occupied"
            if ($request->unit_id) {
                $unit = Unit::findOrFail($request->unit_id);
                $unit->status = 'Occupied';
                $unit->save();
            }
    
            // Commit the transaction
            DB::commit();
    
            return redirect()->route('tenants.index')->with('success', 'Tenant added successfully!');
        } catch (\Exception $e) {
            // Rollback in case of error
            DB::rollBack();
            return redirect()->back()->with('error', 'Error adding tenant: ' . $e->getMessage());
        }
    }
*/
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
