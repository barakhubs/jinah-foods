<?php

namespace App\Http\SmsGateways\Requests;

class FurahaSms
{
    private $username;
    private $apiKey;

    public function __construct($username, $apiKey) {
        $this->username = $username;
        $this->apiKey = $apiKey;
    }

    public function sendSMS($numbers, $message, $type = 'info') {
        $curl = curl_init();
        $payload = json_encode([
            'username' => $this->username,
            'api_key' => $this->apiKey,
            'type' => $type,
            'numbers' => $numbers,
            'message' => $message
        ]);

        curl_setopt_array($curl, array(
            CURLOPT_URL => 'https://api.furahasms.com/api/send_sms',
            CURLOPT_RETURNTRANSFER => true,
            CURLOPT_ENCODING => '',
            CURLOPT_MAXREDIRS => 10,
            CURLOPT_TIMEOUT => 0,
            CURLOPT_FOLLOWLOCATION => true,
            CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
            CURLOPT_CUSTOMREQUEST => 'POST',
            CURLOPT_POSTFIELDS => $payload,
            CURLOPT_HTTPHEADER => array(
                'Content-Type: application/json'
            ),
        ));

        $response = curl_exec($curl);

        if (curl_errno($curl)) {
            return 'Error:' . curl_error($curl);
        }

        curl_close($curl);
        return $response;
    }
}
