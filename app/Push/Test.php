<?php
function sendOneSignalNotification($title, $content, $url) {
    $fields = [
        'app_id' => '1d8a4b41-e3f3-45bc-8c75-3194b19778d4',
        'included_segments' => ['All'],
        'contents' => ['en' => $content],
        'headings' => ['en' => $title],
        'url' => 'https://admin.jinahonestop.com',
    ];

    $fields = json_encode($fields);
    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, 'https://onesignal.com/api/v1/notifications');
    curl_setopt($ch, CURLOPT_HTTPHEADER, [
        'Content-Type: application/json; charset=utf-8',
        'Authorization: Basic MWM3ZGM3YmEtZDZmNC00MWM5LWI0MDQtZjEyNWI1YjVmNjJj',
    ]);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HEADER, false);
    curl_setopt($ch, CURLOPT_POST, true);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, false);

    $response = curl_exec($ch);
    curl_close($ch);

    return $response;
}


echo sendOneSignalNotification("Hello World", "This is a test notification", "https://example.com");
