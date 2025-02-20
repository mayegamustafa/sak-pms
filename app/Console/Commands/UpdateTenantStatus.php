<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Tenant;
use Carbon\Carbon;

class UpdateTenantStatus extends Command
{
    
    protected $signature = 'update:tenant-status';
    protected $description = 'Update tenant status based on lease expiration';

    public function handle()
    {
        $expiredTenants = Tenant::where('lease_end_date', '<', Carbon::today())
                                ->where('is_active', true)
                                ->get();

        foreach ($expiredTenants as $tenant) {
            $tenant->update(['is_active' => false]);
            $tenant->unit()->update(['status' => 'Vacant']);
        }

        $this->info('Tenant statuses updated successfully.');
    }
}
