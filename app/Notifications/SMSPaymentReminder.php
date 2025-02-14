<?php
// app/Notifications/SMSPaymentReminder.php

namespace App\Notifications;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Notifications\Notification;
use Illuminate\Notifications\Messages\TwilioMessage;

class SMSPaymentReminder extends Notification
{
    use Queueable;

    protected $invoice;

    public function __construct($invoice)
    {
        $this->invoice = $invoice;
    }

    public function via($notifiable)
    {
        return ['twilio']; // Send via Twilio SMS
    }

    public function toTwilio($notifiable)
    {
        return (new TwilioMessage)
            ->content("Hello " . $notifiable->name . ", your rent for " . 
                      $this->invoice->property->name . " (UGX " . 
                      number_format($this->invoice->outstanding_amount) . ") is due on " . 
                      $this->invoice->due_date->format('d M Y') . ". Please make payment on time.");
    }
}
