<?php
namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Lease;
use Illuminate\Http\Request;

use App\Models\Invoice;


class PaymentController extends Controller
{
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

}
