<?php
namespace App\Http\Controllers;


use App\Models\Lease;
use App\Models\Tenant;
use App\Models\Property;
use Illuminate\Http\Request;
use Carbon\Carbon;

class LeaseController extends Controller
{

    public function terminate($id)
{
    $lease = Lease::findOrFail($id);
    $lease->update([
        'status' => 'terminated',
    ]);

    return redirect()->route('leases.index')->with('success', 'Lease terminated successfully.');
}


    public function renew($id)
{
    $lease = Lease::findOrFail($id);
    $newEndDate = now()->addYear(); // Extend by one year (you can customize this)

    $lease->update([
        'end_date' => $newEndDate,
        'status' => 'active',
    ]);

    return redirect()->route('leases.index')->with('success', 'Lease renewed until ' . $newEndDate->format('Y-m-d'));
}

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
        ]);
    
        $tenant = Tenant::findOrFail($request->tenant_id);
        $startDate = Carbon::parse($request->start_date);
    
        // Determine lease end date based on tenant lease type
        if ($tenant->lease_type === 'monthly') {
            $endDate = $startDate->addMonth();
        } elseif ($tenant->lease_type === 'yearly') {
            $endDate = $startDate->addYear();
        } else {
            // Fixed-term lease, requires user input
            $request->validate(['end_date' => 'required|date|after:start_date']);
            $endDate = Carbon::parse($request->end_date);
        }
    
        Lease::create([
            'tenant_id' => $request->tenant_id,
            'property_id' => $request->property_id,
            'start_date' => $request->start_date,
            'end_date' => $endDate,
            'status' => 'active',
        ]);
    
        return redirect()->route('leases.index')->with('success', 'Lease created successfully.');
    }
    

    // Show a list of all leases
    public function index()
{
    Lease::where('end_date', '<', now())
         ->where('status', 'active')
         ->update(['status' => 'expired']);

    $leases = Lease::with(['tenant', 'property'])->get();
    return view('leases.index', compact('leases'));
}

   /* public function index()
{
    Lease::where('end_date', '<', now())->where('status', 'active')->update(['status' => 'expired']);
    
    $leases = Lease::with(['tenant', 'property'])->get();
    return view('leases.index', compact('leases'));
} */

  /*  public function index()
    {
        $leases = Lease::with(['tenant', 'property'])->get();
        return view('leases.index', compact('leases'));
    } */

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
            'status' => 'required|in:Active,Expired,Renewed',
        ]);

        $lease = Lease::findOrFail($id);
        $lease->update([
            'tenant_id' => $request->tenant_id,
            'property_id' => $request->property_id,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date,
            'status' => $request->status,
        ]);

        return redirect()->route('leases.index')->with('success', 'Lease updated successfully');
    }

    // Check for expired leases and update their status
    public function checkExpiringLeases()
    {
        $today = Carbon::today();
        Lease::where('end_date', '<', $today)
            ->where('status', 'Active')
            ->update(['status' => 'Expired']);
    }

    // Renew lease
  /*  public function renew($id)
    {
        $lease = Lease::findOrFail($id);
        if ($lease->status === 'Expired') {
            $lease->update([
                'end_date' => Carbon::parse($lease->end_date)->addYear(),
                'status' => 'Renewed',
            ]);
        }

        return redirect()->route('leases.index')->with('success', 'Lease renewed successfully');
    } */

    // Delete lease
    public function destroy($id)
    {
        $lease = Lease::findOrFail($id);
        $lease->delete();

        return redirect()->route('leases.index')->with('success', 'Lease deleted successfully');
    }
}
