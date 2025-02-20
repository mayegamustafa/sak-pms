<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;
   /* protected $fillable = [
        'name', 'email', 'phone_number', 'property_id', 'unit_id',
        'lease_start_date', 'lease_end_date', 'rent_amount', 
        'security_deposit', 'is_active'
    ]; */

    protected $fillable = [
        'name', 'email', 'phone_number', 'property_id', 'unit_id',
        'lease_start_date', 'lease_end_date', 'rent_amount', 
        'security_deposit', 'is_active'
    ];

    protected static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            // Auto-set security deposit if not provided (e.g., 2 months' rent)
            if (is_null($tenant->security_deposit)) {
                $tenant->security_deposit = $tenant->rent_amount * 2; // Example: 2 months' rent as deposit
            }

            // Mark unit as occupied when tenant is added
            if ($tenant->unit_id) {
                Unit::where('id', $tenant->unit_id)->update(['status' => 'Occupied']);
            }
        });

        static::deleting(function ($tenant) {
            // Mark unit as vacant when tenant is removed
            if ($tenant->unit_id) {
                Unit::where('id', $tenant->unit_id)->update(['status' => 'Vacant']);
            }
        });

        static::saving(function ($tenant) {
            $tenant->is_active = $tenant->isActive();
            
            // Update Unit Status
            if ($tenant->unit_id) {
                $tenant->unit()->update(['status' => $tenant->is_active ? 'Occupied' : 'Vacant']);
            }
        });
    }

    
   /* protected static function boot()
    {
        parent::boot();

        static::creating(function ($tenant) {
            // Auto-set security deposit if not provided (e.g., 2 months' rent)
            if (is_null($tenant->security_deposit)) {
                $tenant->security_deposit = $tenant->rent_amount * 2;
            }

            // Mark unit as occupied when tenant is added
            if ($tenant->unit_id) {
                Unit::where('id', $tenant->unit_id)->update(['status' => 'Occupied']);
            }
        });

        static::deleting(function ($tenant) {
            // Mark unit as vacant when tenant is removed
            if ($tenant->unit_id) {
                Unit::where('id', $tenant->unit_id)->update(['status' => 'Vacant']);
            }
        });
    }*/

    public function setIsActiveAttribute($value)
{
    if ($this->lease_end_date && now()->greaterThan($this->lease_end_date)) {
        $this->attributes['is_active'] = false;
    } else {
        $this->attributes['is_active'] = $value;
    }
}

public function isActive()
{
    return $this->lease_end_date && $this->lease_end_date >= now();
}


   

      // Define the relationship with the Property model
      public function property()
      {
          return $this->belongsTo(Property::class);
      }
  
      // Define the relationship with the Unit model
      public function unit()
      {
          return $this->belongsTo(Unit::class);
      }
      public function leases()
      {
          return $this->hasMany(Lease::class); // A tenant can have many leases
      }

      public function tenant() {
        return $this->belongsTo(Tenant::class);
    }
    
 
    
}
