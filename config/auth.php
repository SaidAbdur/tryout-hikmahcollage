<?php

return [
    'defaults' => [
        'guard' => 'student',
        'passwords' => 'admins',
    ],

    'guards' => [
        'student' => ['driver' => 'session', 'provider' => 'students'],
        'admin' => ['driver' => 'session', 'provider' => 'admins'],
    ],

    'providers' => [
        'students' => ['driver' => 'eloquent', 'model' => App\Models\Student::class],
        'admins' => ['driver' => 'eloquent', 'model' => App\Models\Admin::class],
    ],

    'passwords' => [
        'admins' => [
            'provider' => 'admins',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => env('AUTH_PASSWORD_TIMEOUT', 10800),
];
