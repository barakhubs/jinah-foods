<?php
function sendOneSignalNotification($title, $message, $url) {
    $content = array(
        "en" => $message,
    );

    $headings = array(
        "en" => $title,
    );

    $fields = array(
        'app_id' => '41a5fc47-4587-4084-9e84-7478c145e477', // Ensure this is your correct OneSignal App ID
        'included_segments' => ['Total Subscriptions'],
        'contents' => $content,
        'headings' => $headings,
        'url' => $url,
    );

    $fields = json_encode($fields);
    print("\nJSON sent:\n");
    	print($fields);

    $ch = curl_init();
    curl_setopt($ch, CURLOPT_URL, "https://onesignal.com/api/v1/notifications");
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Content-Type: application/json; charset=utf-8', 'accept: application/json',
                                             'Authorization: Basic NGI5YTEzOGUtYTkzNS00OTUzLWJmMjgtNmRmZDc4MTE3NjQ1')); // Ensure this is your correct REST API Key
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, TRUE);
    curl_setopt($ch, CURLOPT_HEADER, FALSE);
    curl_setopt($ch, CURLOPT_POST, TRUE);
    curl_setopt($ch, CURLOPT_POSTFIELDS, $fields);
    curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, TRUE);

    $response = curl_exec($ch);
    if (curl_errno($ch)) {
        return 'cURL error: ' . curl_error($ch);
    }
    $httpcode = curl_getinfo($ch, CURLINFO_HTTP_CODE);
    curl_close($ch);

    return "HTTP code: $httpcode, Response: $response";
}


$response = sendOneSignalNotification("Hello World", "This is a test notification", "https://admin.jinahonestop.com");
	$return["allresponses"] = $response;
	$return = json_encode( $return);

	print("\n\nJSON received:\n");
	print($return);
	print("\n");