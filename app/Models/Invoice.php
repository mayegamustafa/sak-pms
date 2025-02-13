<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Invoice extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id', 'invoice_date', 'due_date', 'amount', 'paid_amount', 'status', 'notes'
    ];

    public function tenant()
    {
        return $this->belongsTo(Tenant::class);
    }

    // app/Models/Invoice.php

public function payments()
{
    return $this->hasMany(Payment::class);
}

// Helper to calculate total paid
public function getTotalPaidAttribute()
{
    return $this->payments->sum('amount');
}

// Helper to calculate outstanding amount
public function getOutstandingAmountAttribute()
{
    return $this->amount - $this->total_paid;
}

// Define the relationship
public function property()
{
    return $this->belongsTo(Property::class, 'property_id'); 
}

protected $casts = [
    'issue_date' => 'datetime',
    'due_date' => 'datetime',
];

}

