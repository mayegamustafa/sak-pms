<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Property extends Model
{
    use HasFactory;
    //use HasFactory;

   // protected $fillable = ['name', 'location', 'units', 'price_per_unit'];

    public function owner()
    {
        return $this->belongsTo(User::class, 'owner_id');
    }

  //  use HasFactory;

    //protected $fillable = ['name', 'location', 'type', 'owner_id'];

    protected $fillable = [
        'name',
        'type',
        'num_units',
        'num_floors',
        'location',      // Add this line
        'owner_id',
        'manager_id',
    ];
    public function units()
    {
        return $this->hasMany(Unit::class);
    }

   
    public function manager()
    {
        return $this->belongsTo(User::class, 'manager_id');
    }



    public function leases()
    {
        return $this->hasMany(Lease::class); // A property can have many leases
    }
    public function invoices()
    {
        return $this->hasMany(Invoice::class, 'property_id');
    }

    public function tenant() {
        return $this->belongsTo(Tenant::class);
    }
    
    public function property() {
        return $this->belongsTo(Property::class);
    }
    
    
}
