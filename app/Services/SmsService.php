<?php
namespace App\Services;

use Twilio\Rest\Client;
use App\Models\Tenant; // Make sure you have a Tenant model

class SmsService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendBulkSms()
    {
        $tenants = Tenant::all(); // Fetch all tenants

        foreach ($tenants as $tenant) {
            $message = "Hello {$tenant->name}, your balance is UGX {$tenant->balance}. Reason: {$tenant->reason}. Please clear it. Thank you!";
            
            try {
                $this->twilio->messages->create($tenant->phone_number, [
                    'from' => env('TWILIO_FROM'),
                    'body' => $message
                ]);
            } catch (\Exception $e) {
                // Log errors if any message fails
                \Log::error("SMS failed for {$tenant->phone}: " . $e->getMessage());
            }
        }

        return "Bulk SMS sent successfully!";
    }
}
