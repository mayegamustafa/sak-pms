<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Tenant extends Model
{
    use HasFactory;
    protected $fillable = [
        'name', 
        'property_id', 
        'unit_id', 
        'lease_start_date', 
        'rent_amount',
        '_token', // Add _token here
    ];

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
}
