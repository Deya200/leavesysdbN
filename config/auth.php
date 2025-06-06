<?php

return [

    'defaults' => [
        // ✅ Change the default guard and passwords to use the users provider
        'guard' => 'web',
        'passwords' => 'users',
    ],

    'guards' => [
        // ✅ The default guard now correctly points to the users provider.
        'web' => [
            'driver' => 'session',
            'provider' => 'users',
        ],
    ],

    'providers' => [
        // ✅ Ensure the users provider uses the User model, NOT Employee.
        'users' => [
            'driver' => 'eloquent',
            'model' => App\Models\User::class,
        ],
        'employees' => [
            'driver' => 'eloquent',
            'model' => App\Models\Employee::class,
        ],
    ],

    'passwords' => [
        // ✅ Use the users provider for password resets.
        'users' => [
            'provider' => 'users',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
        'employees' => [
            'provider' => 'employees',
            'table' => 'password_reset_tokens',
            'expire' => 60,
            'throttle' => 60,
        ],
    ],

    'password_timeout' => 10800,
];
