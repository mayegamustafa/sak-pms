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

    protected $fillable = ['name', 'location', 'type', 'owner_id'];

    public function units()
    {
        return $this->hasMany(Unit::class);
    }

    public function leases()
    {
        return $this->hasMany(Lease::class); // A property can have many leases
    }
}
