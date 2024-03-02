<?php

require_once '../../vendor/autoload.php'; // Include Composer autoload

use Berkayk\OneSignal\OneSignalFacade;

// Initialize OneSignal facade with Laravel application
$app = require_once '../../bootstrap/app.php';
$app->make('Illuminate\Contracts\Console\Kernel')->bootstrap();
$app->make('config')->set('services.onesignal', [
    'app_id' => config('onesignal.app_id'),
    'rest_api_key' => config('onesignal.rest_api_key'),
    'user_auth_key' => config('onesignal.user_auth_key'),
    'guzzle_client_timeout' => config('onesignal.guzzle_client_timeout'),
]);

// Send a test message to all users
$response = OneSignalFacade::sendNotificationToUser(
    $message = 'Hello testing',
    '511cba9d-9b95-4e35-9a89-bb966d6665dd',
    $data = null,
    $buttons = null,
    $schedule = null
);

// Handle response
if ($response['success']) {
    echo "Message sent successfully to all users.";
} else {
    echo "Failed to send message. Error: " . $response['body'];
}
