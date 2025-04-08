<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use Illuminate\Http\Request;
use App\Models\Property;
use App\Models\Unit;
use App\Services\SmsService;
use Illuminate\Support\Facades\DB;
use Twilio\Rest\Client;

class AdminController extends Controller
{
    // Dashboard for admin
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        // Base query (unfiltered initially)
        $baseQuery = Tenant::query();

        // Apply filters if provided
        if ($request->has('start_date') && $request->has('end_date')) {
            $baseQuery->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->has('status')) {
            $baseQuery->where('status', $request->status);
        }

        // Clone for each specific purpose
        $activeTenants = (clone $baseQuery)
            ->where('is_active', true)
            ->with('unit')
            ->get();

        $pastTenants = (clone $baseQuery)
            ->where('is_active', false)
            ->with('unit')
            ->paginate(5, ['*'], 'past_page');

        $vacantUnits = Unit::where('is_occupied', false)->paginate(10);

        // Monthly tenant registration stats
        $tenantStats = Tenant::selectRaw('MONTH(created_at) as month, COUNT(*) as total')
            ->whereYear('created_at', $year)
            ->groupByRaw('MONTH(created_at)')
            ->orderByRaw('MONTH(created_at)')
            ->pluck('total', 'month')
            ->toArray();

        // Monthly rent collection stats
        $rentCollectionStats = Tenant::selectRaw('MONTH(payment_date) as month, SUM(amount_due) as total_collected')
            ->whereNotNull('payment_date')
            ->whereYear('payment_date', $year)
            ->groupByRaw('MONTH(payment_date)')
            ->orderByRaw('MONTH(payment_date)')
            ->pluck('total_collected', 'month')
            ->toArray();

        // Ensure all months exist
        $tenantStats = $this->fillMissingMonths($tenantStats);
        $rentCollectionStats = $this->fillMissingMonths($rentCollectionStats);

        // Accurate stats using is_active + units
        $totalActiveTenants = Tenant::where('is_active', true)->count();
        $totalPastTenants = Tenant::where('is_active', false)->count();
        $totalOccupiedUnits = Unit::where('is_occupied', true)->count();
        $occupancyRate = $this->calculateOccupancyRate();

        return view('admin.dashboard', compact(
            'tenantStats',
            'rentCollectionStats',
            'year',
            'totalActiveTenants',
            'totalPastTenants',
            'totalOccupiedUnits',
            'occupancyRate',
            'activeTenants',
            'pastTenants',
            'vacantUnits'
        ));
    }

    // Ensure months 1-12 are always present
    private function fillMissingMonths($data)
    {
        $filledData = array_fill(1, 12, 0); // Jan - Dec
        foreach ($data as $month => $value) {
            $filledData[(int) $month] = $value;
        }
        return $filledData;
    }

    // Calculates occupancy rate based on Unit model
    private function calculateOccupancyRate()
    {
        $totalUnits = Unit::count();
        $occupiedUnits = Unit::where('is_occupied', true)->count();
        return $totalUnits > 0 ? ($occupiedUnits / $totalUnits) * 100 : 0;
    }

    // Export tenants to CSV
    public function export()
    {
        $tenants = Tenant::all();

        $data = $tenants->map(function ($tenant) {
            return [
                'Name' => $tenant->name,
                'Unit' => $tenant->unit ? $tenant->unit->unit_number : 'N/A',
                'Lease End' => $tenant->lease_end_date,
                'Amount Due' => $tenant->amount_due,
                'Status' => ucfirst($tenant->status),
            ];
        });

        $csv = \League\Csv\Writer::createFromString('');
        $csv->insertOne(array_keys($data->first()));
        $csv->insertAll($data);

        $filename = 'tenant_export_' . now()->format('Y_m_d') . '.csv';

        return response((string)$csv)
            ->header('Content-Type', 'text/csv')
            ->header('Content-Disposition', 'attachment; filename="' . $filename . '"');
    }
}
