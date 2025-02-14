<?php

namespace App\Services;

use Twilio\Rest\Client;

class SmsService
{
    protected $twilio;

    public function __construct()
    {
        $this->twilio = new Client(env('TWILIO_SID'), env('TWILIO_AUTH_TOKEN'));
    }

    public function sendSms($to, $message)
    {
        return $this->twilio->messages->create($to, [
            'from' => env('TWILIO_FROM'),  // Ensure this matches .env
            'body' => $message
        ]);
    }
    

}
