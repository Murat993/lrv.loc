<?php

return [
    'driver' => env('SMS_DRIVER', 'smsc'),
    'drivers' => [
        'smsc' => [
            'app_id' => env('SMS_SMS_C_APP_ID'),
            'url' => env('SMS_SMS_C_APP_URL'),
        ],
    ],
];
