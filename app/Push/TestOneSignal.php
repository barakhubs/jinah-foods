<?php

namespace App\Push;
use Ladumor\OneSignal\OneSignal;

class TestOneSignal {
    public static function sendMessage($message, $fields) {
        // Assuming $fields already includes 'include_player_ids'
        $response = OneSignal::sendPush($fields, $message);
        return $response; // Optionally return the response from OneSignal
    }
}

// Usage
$fields = [
    'include_player_ids' => ['9b50e633-1b00-441e-b93d-6afa3e521180'],
    // Add additional fields as needed, e.g., headings, contents, etc.
    'contents' => ['en' => 'push notification example'], // Example content
];

// Static call
TestOneSignal::sendMessage('Your message here', $fields);
