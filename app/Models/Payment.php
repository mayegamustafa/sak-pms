<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Payment extends Model
{
    use HasFactory;

    protected $fillable = [
        'tenant_id','invoice_id','amount_paid', 'amount', 'payment_date', 'payment_method', 'reference', 'notes' , 'for_month', 'for_year'
    ];

    public function invoice()
    {
        return $this->belongsTo(Invoice::class);
    }


public function tenant()
{
    return $this->belongsTo(Tenant::class, 'id');
}

    
}
