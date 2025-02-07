<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Lease extends Model
{
    use HasFactory;
     // Add tenant_id to the fillable property
     protected $fillable = [
        'tenant_id',
        'property_id',
        'start_date',
        'end_date',
    ];

       // Define the tenant relationship (assumes the lease has a tenant_id column)
       public function tenant()
       {
           return $this->belongsTo(Tenant::class); // Assuming Tenant is a model and has the proper relationship
       }
   
       // Define the property relationship (assumes the lease has a property_id column)
       public function property()
       {
           return $this->belongsTo(Property::class); // Assuming Property is a model and has the proper relationship
       }
}
