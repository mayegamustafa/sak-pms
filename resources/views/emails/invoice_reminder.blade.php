<!-- resources/views/emails/invoice_reminder.blade.php -->

<p>Dear {{ $invoice->tenant->name }},</p>

<p>This is a reminder that your invoice #{{ $invoice->id }} is due on {{ $invoice->due_date }}.</p>

<p>Outstanding Amount: UGX {{ number_format($invoice->amount - $invoice->paid_amount) }}</p>

<p>Please make your payment as soon as possible to avoid any penalties.</p>

<p>Thank you!</p>
