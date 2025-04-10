<?php
namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Lease;
use Illuminate\Http\Request;
use App\Models\Tenant; // Make sure to import the Tenant model
use App\Models\Unit; 
use App\Models\Invoice;


class PaymentController extends Controller
{

    public function index()
    {
        // Fetch payments from the database
        $payments = Payment::all(); // You can add additional filters or pagination here
// Paginate payments with 10 records per page
$payments = Payment::paginate(10);
// Eager load the tenant relationship
$payments = Payment::with('tenant')->paginate(10);
        // Return a view with the payments
        return view('payments.index', compact('payments'));
    }
    
    public function create1()
    {
        // Fetch tenants and units for the dropdowns
        $tenants = Tenant::all();
        $units = Unit::all();

        return view('payments.create1', compact('tenants', 'units'));
    }

    public function create($invoice_id)
    {
        $invoice = Invoice::with('tenant')->findOrFail($invoice_id);
        return view('payments.create', compact('invoice'));
    }

    public function store(Request $request, $invoice_id)
    {
        $invoice = Invoice::findOrFail($invoice_id);

        $request->validate([
            'amount' => 'required|numeric|min:1|max:' . $invoice->outstanding_amount,
            'payment_date' => 'required|date',
            'payment_method' => 'nullable|string',
            'reference' => 'nullable|string',
        ]);

        // Create Payment
        $payment = Payment::create([
            'invoice_id' => $invoice->id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
            'payment_method' => $request->payment_method,
            'reference' => $request->reference,
            'notes' => $request->notes,
        ]);

        // Update Invoice Status
        $totalPaid = $invoice->payments()->sum('amount') + $request->amount;

        if ($totalPaid >= $invoice->amount) {
            $invoice->status = 'paid';
        } elseif ($invoice->due_date < now()) {
            $invoice->status = 'overdue';
        } else {
            $invoice->status = 'pending';
        }

        $invoice->paid_amount = $totalPaid;
        $invoice->save();

        return redirect()->route('invoices.index')->with('success', 'Payment recorded successfully!');
    }


    public function store1(Request $request)
{
    $request->validate([
        'tenant_id' => 'required|exists:tenants,id',
        'unit_id' => 'nullable|exists:units,id',
        'amount_paid' => 'required|numeric|min:0.01',
        'payment_date' => 'required|date',
        'payment_method' => 'nullable|string',
        'for_month' => 'required|integer|min:1|max:12',
        'for_year' => 'required|integer|min:2000',
        'invoice_id' => 'nullable|exists:invoices,id', 

    ]);

    Payment::create([
        //'tenant_id' => $request->tenant_id,
        'tenant_id' => $request->tenant_id ?? 1,  // Default tenant ID (or handle as needed)
        'unit_id' => $request->unit_id,
        'amount' => $request->amount_paid,
        'amount_paid' => $request->amount_paid,
        'payment_date' => $request->payment_date,
        'payment_method' => $request->payment_method,
        'for_month' => $request->for_month,
        'for_year' => $request->for_year,
        'invoice_id' => $this->generateInvoiceId(),  
    ]);

    return redirect()->route('payments.create1')->with('success', 'Payment recorded successfully!');
}
// Inside PaymentController.php

public function generateInvoiceId()
{
    // Logic to generate a unique invoice ID
    // For example, using the current timestamp and a random number
    return 'INV-' . time() . '-' . rand(1000, 9999);
}

}
