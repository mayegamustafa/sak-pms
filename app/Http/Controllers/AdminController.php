<?php

namespace App\Http\Controllers;

use App\Models\Tenant;
use App\Models\Property;
use App\Models\Unit;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Carbon\Carbon;

class AdminController extends Controller
{
    public function index(Request $request)
    {
        $year = $request->get('year', now()->year);

        // Filters
        $baseQuery = Tenant::query();

        if ($request->has('start_date') && $request->has('end_date')) {
            $baseQuery->whereBetween('created_at', [$request->start_date, $request->end_date]);
        }

        if ($request->has('status')) {
            $baseQuery->where('status', $request->status);
        }

        // Fetch data
        $activeTenants = (clone $baseQuery)
            ->where('is_active', true)
            ->with('unit')
            ->get();

        $pastTenants = (clone $baseQuery)
            ->where('is_active', false)
            ->with('unit')
            ->paginate(5, ['*'], 'past_page');

        $vacantUnits = Unit::where('is_occupied', false)->paginate(10);

        // Monthly tenant registrations
        $tenantStats = $this->getMonthlyStats('tenants', 'created_at', $year);

        // Monthly rent collection from `payments` table
        $rentCollectionStats = $this->getMonthlyStats('payments', 'payment_date', $year, 'amount_paid');

        // Occupancy & counts
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

    /**
     * Generates monthly totals from a table.
     */
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

    

    /**
     * Fill missing months with 0.
     */
    private function fillMissingMonths($data)
    {
        $filledData = array_fill(1, 12, 0);
        foreach ($data as $month => $value) {
            $filledData[(int) $month] = $value;
        }
        return $filledData;
    }

    /**
     * Calculate occupancy rate based on units.
     */
    private function calculateOccupancyRate()
    {
        $totalUnits = Unit::count();
        $occupiedUnits = Unit::where('is_occupied', true)->count();
        return $totalUnits > 0 ? round(($occupiedUnits / $totalUnits) * 100, 2) : 0;
    }

    /**
     * Export tenants to CSV.
     */
    public function export()
    {
        $tenants = Tenant::with('unit')->get();

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
