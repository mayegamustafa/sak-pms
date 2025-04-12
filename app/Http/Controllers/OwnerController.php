<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;


use App\Models\Tenant;
use App\Models\Property;
use App\Models\Unit;

use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Auth;
use Carbon\Carbon;

class OwnerController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        $user = Auth::user();

        // Base tenant query for filtering
        $baseQuery = Tenant::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $baseQuery->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->has('status')) {
            $baseQuery->where('status', $request->status);
        }

        // Apply role-based filters
        if ($user->role === 'Manager') {
            $propertyIds = $user->managedProperties->pluck('id');
            $baseQuery->whereHas('unit.property', function ($q) use ($propertyIds) {
                $q->whereIn('id', $propertyIds);
            });
        } elseif ($user->role === 'Owner') {
            $propertyIds = $user->ownedProperties->pluck('id');
            $baseQuery->whereHas('unit.property', function ($q) use ($propertyIds) {
                $q->whereIn('id', $propertyIds);
            });
        }

        // Fetch tenant and unit data
        $activeTenants = (clone $baseQuery)
            ->where('is_active', true)
            ->with('unit')
            ->paginate(10);

           

        $pastTenants = (clone $baseQuery)
            ->where('is_active', false)
            ->with('unit')
            ->paginate(5, ['*'], 'past_page');

        $vacantUnits = Unit::where('is_occupied', false);

        if (in_array($user->role, ['Manager', 'Owner'])) {
            $propertyIds = $user->role === 'Manager' ? $user->managedProperties->pluck('id') : $user->ownedProperties->pluck('id');
            $vacantUnits->whereIn('property_id', $propertyIds);
        }

        $vacantUnits = $vacantUnits->paginate(10);

        $tenantStats = $this->getMonthlyStats('tenants', 'created_at', $year);
        $rentCollectionStats = $this->getMonthlyStats('payments', 'payment_date', $year, 'amount_paid');

        $totalActiveTenants = $activeTenants->count();
        $totalPastTenants = $pastTenants->total();
        $totalOccupiedUnits = Unit::where('status', 'Occupied')->count();
        $occupancyRate = $this->calculateOccupancyRate('status');

        $monthlyNewTenants = Tenant::whereYear('created_at', $year)
    ->whereMonth('created_at', now()->month)
    ->count();

$monthlyRentCollected = DB::table('payments')
    ->whereYear('payment_date', $year)
    ->whereMonth('payment_date', now()->month)
    ->sum('amount_paid');

$vacantUnitsCount = Unit::where('is_occupied', false)->count();
$totalUnitsCount = Unit::count();


        return view('owner.dashboard', compact(
            'tenantStats',
            'rentCollectionStats',
            'year',
            'totalActiveTenants',
            'totalPastTenants',
            'totalOccupiedUnits',
            'occupancyRate',
            'activeTenants',
            'pastTenants',
            'vacantUnits',

            
    'monthlyNewTenants',
    'monthlyRentCollected',
    'vacantUnitsCount',
    'totalUnitsCount'
        ));
    }

    private function getMonthlyStats($table, $dateColumn, $year, $sumColumn = '*')
    {
        $monthlyData = DB::table($table)
            ->selectRaw("MONTH($dateColumn) as month, " .
                ($sumColumn === '*' ? "COUNT(*)" : "SUM($sumColumn)") . " as total")
            ->whereYear($dateColumn, $year)
            ->groupByRaw("MONTH($dateColumn)")
            ->pluck('total', 'month')
            ->toArray();

        return $this->fillMissingMonths($monthlyData);
    }

    private function fillMissingMonths($data)
    {
        $filledData = array_fill(1, 12, 0);
        foreach ($data as $month => $value) {
            $filledData[(int) $month] = $value;
        }
        return $filledData;
    }

    private function calculateOccupancyRate($checkColumn = 'is_occupied')
    {
        $totalUnits = Unit::count();

        $occupiedUnits = $checkColumn === 'status'
            ? Unit::where('status', 'Occupied')->count()
            : Unit::where('is_occupied', true)->count();

        return $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 2) : 0;
    }

    public function export()
    {
        $user = Auth::user();

        $tenants = Tenant::with('unit');

        if ($user->role === 'Manager') {
            $propertyIds = $user->managedProperties->pluck('id');
            $tenants->whereHas('unit.property', function ($q) use ($propertyIds) {
                $q->whereIn('id', $propertyIds);
            });
        } elseif ($user->role === 'Owner') {
            $propertyIds = $user->ownedProperties->pluck('id');
            $tenants->whereHas('unit.property', function ($q) use ($propertyIds) {
                $q->whereIn('id', $propertyIds);
            });
        }

        $tenants = $tenants->get();

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
