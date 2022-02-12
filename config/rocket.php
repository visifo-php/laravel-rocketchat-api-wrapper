<?php

return [
    'url' => env('ROCKET_URL'),
    'authToken' => env('ROCKET_AUTH_TOKEN'),
    'user' => [
        'id' => env('ROCKET_USER_ID'),
        'password' => env('ROCKET_USER_PASSWORD'),
    ],
    'timeout' => env('ROCKET_TIMEOUT', 0),
    'retries' => env('ROCKET_RETRIES', 1),
    'sleep' => env('ROCKET_SLEEP', 0),
    'debug_logs_enabled' => env('ROCKET_DEBUG_LOGS', false),
];
