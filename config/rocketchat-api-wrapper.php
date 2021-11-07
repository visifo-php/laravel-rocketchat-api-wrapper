<?php

return [
    'url' => env('ROCKET_URL', 'localhost:3000'),
    'authToken' => env('ROCKET_AUTH_TOKEN', 'rocket_auth_token'),
    'user' => [
        'id' => env('ROCKET_USER_ID', 'rocket_user_id'),
        'name' => env('ROCKET_USER_NAME', 'rocket_user_name'),
        'password' => env('ROCKET_USER_PASSWORD', 'rocket_user_password'),
    ],
];