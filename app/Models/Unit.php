<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Unit extends Model
{
    use HasFactory;

   // protected $fillable = ['property_id', 'unit_number', 'rent_amount', 'tenant_name', 'status'];

    public function property()
    {
        return $this->belongsTo(Property::class);
    }
  /* protected $fillable = [
        'property_id',
        'unit_number',
        'floor',
        'rent_amount',
        'status',
    ];
    */
    protected $fillable = ['property_id', 'unit_number', 'floor', 'rent_amount', 'status'];

    // App\Models\Unit.php

protected static function booted()
{
    static::saving(function ($unit) {
        $unit->is_occupied = $unit->status === 'Occupied';
    });
}

}
