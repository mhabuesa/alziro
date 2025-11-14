<?php

namespace App\Traits;

trait SendSmsTraits
{
    public function sendSms($phone, $message)
    {
        $url = "http://bulksmsbd.net/api/smsapi";
        $apiKey = env('BULK_SMS_API_KEY');
        $senderId = env('BULK_SMS_SENDER_ID');
        $number = $phone;
        $message = $message;

        $data = [
            "api_key" => $apiKey,
            "senderid" => $senderId,
            "number" => $number,
            "message" => $message
        ];
        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, $url);
        curl_setopt($ch, CURLOPT_POST, 1);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $data);
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);
        $response = curl_exec($ch);
        curl_close($ch);
    }
}
