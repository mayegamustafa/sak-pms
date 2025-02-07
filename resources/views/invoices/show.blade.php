<!-- resources/views/invoices/show.blade.php -->

<h1>Invoice Details</h1>
<!-- Display invoice details here -->

<!-- Button to generate PDF -->
<a href="{{ route('invoices.pdf', ['id' => $invoice->id]) }}" class="btn btn-primary">
    Download PDF
</a>
