<?php
namespace App\Push;

use App\Push\PushNotification;

$pushNotification = new PushNotification();
$message = 'Hello world!';
$response = $pushNotification->sendWebNotification('Jinah Foods Notification', $message);

// Decode the response and check for errors
$responseData = json_decode($response, true);
if (isset($responseData['errors'])) {
    echo "Error sending notification: " . implode(', ', $responseData['errors']);
} else {
    echo "Notification sent successfully!";
}
