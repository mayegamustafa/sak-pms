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
}

