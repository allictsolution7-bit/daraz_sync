<?php

// app/Services/SMSService.php
namespace App\Services;

use Illuminate\Support\Facades\Http;

class SMSService
{
    protected $url;
    protected $api_key;
    protected $senderid;

    public function __construct()
    {
        $this->url = env('SMS_API_URL', 'https://bulksmsbd.net/api/smsapi');
        $this->api_key = env('SMS_API_KEY');
        $this->senderid = env('SMS_SENDER_ID');
    }

    public function sendSMS($number, $message)
    {
        $data = [
            "api_key" => $this->api_key,
            "senderid" => $this->senderid,
            "number" => $number,
            "message" => $message,
        ];

        $response = Http::timeout(30)->asForm()->post($this->url, $data);
        return $response->body();
    }
}
