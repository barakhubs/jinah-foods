<?php
return [

    /*
    |-------------------------------------------------------------------------------------------
    | URL - One Signal have different endpoint.
    |-------------------------------------------------------------------------------------------
    |
    */
    'url'    => env('ONE_SIGNAL_URL', 'https://onesignal.com/api/v1/'),

    /*
    |-------------------------------------------------------------------------------------------
    | App Id - One Signal have different app id for every app.
    |
    | Based on App you are using, you can change the App Id here and specify here
    |-------------------------------------------------------------------------------------------
    |
    */
    'app_id' => env('ONE_SIGNAL_APP_ID', '1d8a4b41-e3f3-45bc-8c75-3194b19778d4'),

    /*
    |-------------------------------------------------------------------------------------------
    | Authorize - One Signal have different Authorize for every app.
    |
    | Based on App you are using, you can change the Authorize here and specify here
    |-------------------------------------------------------------------------------------------
    |
    */

    'authorize'       => env('ONE_SIGNAL_AUTHORIZE','NjAzY2QzNzgtMGU2NC00OTAwLThjMGEtMTFhMzYwNTYyY2M3'),

    /*
    |-------------------------------------------------------------------------------------------
    | mutable_content - Always defaults to true and cannot be turned off. Allows tracking of notification receives
    | and changing of the notification content in app before it is displayed.
    |-------------------------------------------------------------------------------------------
    |
    */
    'mutable_content' => env('ONE_SIGNAL_MUTABLE_CONTENT', true),

    /*
    |-------------------------------------------------------------------------------------------
    | Auth Key - One Signal have Auth key of account.
    |
    | You can manage apps
    |-------------------------------------------------------------------------------------------
    |
   */
    'auth_key' => env('ONE_SIGNAL_AUTH_KEY', 'Y2EyM2Q5MTMtMDA0Yi00ODA2LWExZGUtYzFiYjQ1YTY5N2Mz')
];
