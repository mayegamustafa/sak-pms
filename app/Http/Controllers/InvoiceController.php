<?php


// app/Http/Controllers/InvoiceController.php

namespace App\Http\Controllers;

use App\Models\Invoice;
use App\Models\Tenant;
use Illuminate\Http\Request;
use Barryvdh\DomPDF\Facade as PDF;
use Dompdf\Dompdf;
use Dompdf\Options;


class InvoiceController extends Controller
{

    public function download($id)
    {
        // Fetch invoice data
        $invoice = Invoice::with(['tenant', 'property', 'payments'])->findOrFail($id);

        // Initialize dompdf
        $dompdf = new Dompdf();
        
        // Load the view and render the HTML content
        $html = view('invoices.pdf.invoice', compact('invoice'))->render();
        
        // Load HTML to Dompdf
        $dompdf->loadHtml($html);
        
        // (Optional) Set paper size (A4 by default)
        $dompdf->setPaper('A4', 'portrait');
        
        // Render PDF (first pass)
        $dompdf->render();
        
        // Stream the PDF (download)
        return $dompdf->stream('Invoice_' . $invoice->invoice_number . '.pdf');
    }
   /* public function download($id)
{
    $invoice = Invoice::with(['tenant', 'property', 'payments'])->findOrFail($id);

    $pdf = PDF::loadView('invoices.pdf.invoice', compact('invoice'));

    return $pdf->download('Invoice_' . $invoice->invoice_number . '.pdf');
}
*/
    public function index()
    {
        $invoices = Invoice::with('tenant')->paginate(10);
        return view('invoices.index', compact('invoices'));
    }
    

    public function create()
    {
        $tenants = Tenant::all();
        return view('invoices.create', compact('tenants'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'amount' => 'required|numeric|min:0',
        ]);

        Invoice::create($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice created successfully!');
    }

    public function edit(Invoice $invoice)
    {
        $tenants = Tenant::all();
        return view('invoices.edit', compact('invoice', 'tenants'));
    }

    public function update(Request $request, Invoice $invoice)
    {
        $request->validate([
            'tenant_id' => 'required|exists:tenants,id',
            'invoice_date' => 'required|date',
            'due_date' => 'required|date|after_or_equal:invoice_date',
            'amount' => 'required|numeric|min:0',
            'paid_amount' => 'nullable|numeric|min:0',
        ]);

        $invoice->update($request->all());

        return redirect()->route('invoices.index')->with('success', 'Invoice updated successfully!');
    }

    public function destroy(Invoice $invoice)
    {
        $invoice->delete();
        return redirect()->route('invoices.index')->with('success', 'Invoice deleted successfully!');
    }

    // app/Http/Controllers/InvoiceController.php

public function payInvoice(Request $request, Invoice $invoice)
{
    $request->validate([
        'paid_amount' => 'required|numeric|min:0|max:' . ($invoice->amount - $invoice->paid_amount),
    ]);

    $invoice->paid_amount += $request->paid_amount;

    if ($invoice->paid_amount >= $invoice->amount) {
        $invoice->status = 'paid';
    } else {
        $invoice->status = 'pending';
    }

    $invoice->save();

    return redirect()->route('invoices.index')->with('success', 'Payment added successfully!');
}

// app/Http/Controllers/InvoiceController.php



public function generateInvoicePDF(Invoice $invoice)
{
    $pdf = PDF::loadView('invoices.pdf', compact('invoice'));
    return $pdf->download('invoice_' . $invoice->id . '.pdf');
}

public function show($id)
    {
        $invoice = Invoice::findOrFail($id);
        return view('invoices.show', compact('invoice'));
    }
    
}
