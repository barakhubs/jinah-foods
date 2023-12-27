<?php
namespace App\Push;

class PushNotificaiton {
    function sendMessage($title, $message, $player_id) {
        $content = array(
            "en" => $message,
        );

        $headings = array(
            "en" =>  $title,
        );

        $fields = array(
            'app_id' => '41a5fc47-4587-4084-9e84-7478c145e477', // Replace with your OneSignal App ID
            'include_player_ids' => array($player_id),
            'data' => array("foo" => "bar"),
            'contents' => $content,
            'headings' => $headings,
            'small_icon' => 'https://example.com/icon.png' // Add the icon URL here
        );

        $fields = json_encode($fields);

        $ch = curl_init();
        curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
        curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8',
                                                 'Authorization: Basic NGI5YTEzOGUtYTkzNS00OTUzLWJmMjgtNmRmZDc4MTE3NjQ1')); // Replace with your OneSignal REST API Key
        curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
        curl_setopt($ch, CURLOPT_HEADER, FALSE);
        curl_setopt($ch, CURLOPT_POST, TRUE);
        curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
        curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, FALSE);

        $response = curl_exec($ch);
        curl_close($ch);

        echo "Response: " . $response;
    }
}
