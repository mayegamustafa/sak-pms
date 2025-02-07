<?php

// app/Console/Commands/SendInvoiceReminders.php

namespace App\Console\Commands;

use App\Models\Invoice;
use Illuminate\Console\Command;
use Illuminate\Support\Facades\Mail;
use App\Mail\InvoiceReminderMail;

class SendInvoiceReminders extends Command
{
    protected $signature = 'invoices:remind';
    protected $description = 'Send reminders for upcoming or overdue invoices';

    public function handle()
    {
        $today = now();
        $invoices = Invoice::where('due_date', '<', $today)
                            ->where('status', '!=', 'paid')
                            ->get();

        foreach ($invoices as $invoice) {
            Mail::to($invoice->tenant->email)->send(new InvoiceReminderMail($invoice));
        }

        $this->info('Reminders sent successfully.');
    }
}

