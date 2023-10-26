<?php

namespace App\Services;


use Exception;
use GuzzleHttp\Client;
use Illuminate\Support\Facades\Log;

class FirebaseService
{
    public function sendNotification($data, $fcmToken, $topicName) : Exception | string
    {
        $final = [
            'to' => 'ez3NmtCcQDSnvMSmGj4IuV:APA91bEJUcm8E2WVllnWa8Dz4KJjbkJ1Yz21Vd2RKMvbPRlkETTt-PWsYuLJi86tT0pATHAB1xr3_YEvqwpT7Wj7MeDEzfCn3Hv_2Cw_S0_StWn3nScrnFqCn7PDkvG71KOXa2xhgPi6',
            'notification' => [
                'title' => 'Testing notifications',
                'body' => 'This is a test notification body'
            ],
        ];

        $url = 'https://fcm.googleapis.com/fcm/send';

        $client  = new Client();
        $headers = [
            'Authorization' => "key=AAAAMU-0L4Q:APA91bFK8UfzChc-kBRdV6nQo7kCeQcsFF5u4wREBJDVDDW1C9hw1Jly73X0wi-d1rkLCwMi7q1DtoqByCuuj6dmZs164fVmj4vzXEA4s2s1jdMqPT0b---kWtGxWTMD0yHA3eqnWtkA",
            'Content-Type'  => 'application/json',
        ];

        try {
            $result = $client->post($url, [
                'headers' => $headers,
                "body"    => json_encode($final)
            ]);
            return $result->getBody()->getContents();
        } catch (Exception $e) {
            Log::info($e->getMessage());
            return $e->getMessage();
        }
    }
}
