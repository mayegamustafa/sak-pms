<?php
namespace App\Http\Controllers;
use App\Models\Payment;
use App\Models\Lease;
use Illuminate\Http\Request;


class PaymentController extends Controller
{
    public function create()
    {
        $leases = Lease::all();
        return view('payments.create', compact('leases'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'lease_id' => 'required|exists:leases,id',
            'amount' => 'required|numeric',
            'payment_date' => 'required|date',
        ]);

        Payment::create([
            'tenant_id' => Lease::find($request->lease_id)->tenant_id,
            'lease_id' => $request->lease_id,
            'amount' => $request->amount,
            'payment_date' => $request->payment_date,
        ]);

        return redirect()->route('payments.index')->with('success', 'Payment recorded successfully');
    }

 
    public function report()
    {
        $leases = Lease::with('payments', 'property')->get();
        return view('reports.payment', compact('leases'));
    }



}
