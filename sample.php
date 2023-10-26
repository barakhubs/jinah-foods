<?php
// $curl = curl_init();

// curl_setopt_array($curl, array(
//   CURLOPT_URL => 'https://api.furahasms.com//api/send_sms?username=29479314&api_key=1EunqdciOYgJGIMi56UzJAEsT7f0clYM&numbers=0758029195%2C%200776278284&message=Hello%20World&type=info',
//   CURLOPT_RETURNTRANSFER => true,
//   CURLOPT_ENCODING => '',
//   CURLOPT_MAXREDIRS => 10,
//   CURLOPT_TIMEOUT => 0,
//   CURLOPT_FOLLOWLOCATION => true,
//   CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
//   CURLOPT_CUSTOMREQUEST => 'GET',
// ));

// $response = curl_exec($curl);

// if (curl_errno($curl)) {
//     echo 'Error:' . curl_error($curl);
// }

// curl_close($curl);

// echo $response;

$curl = curl_init();

curl_setopt_array($curl, array(
  CURLOPT_URL => 'https://api.furahasms.com//api/send_sms',
  CURLOPT_RETURNTRANSFER => true,
  CURLOPT_ENCODING => '',
  CURLOPT_MAXREDIRS => 10,
  CURLOPT_TIMEOUT => 0,
  CURLOPT_FOLLOWLOCATION => true,
  CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
  CURLOPT_CUSTOMREQUEST => 'POST',
  CURLOPT_POSTFIELDS =>'{
    "username": "51856485",
    "api_key": "KH7lfPYjb20McfanaC5qeAZ7kHTkVzr6",
    "type": "info",
    "numbers": "256758029195,0773034311",
    "message": "Hello World"
}',
  CURLOPT_HTTPHEADER => array(
    'Content-Type: application/json'
  ),
));

$response = curl_exec($curl);

if (curl_errno($curl)) {
    echo 'Error:' . curl_error($curl);
}

curl_close($curl);

echo $response;

