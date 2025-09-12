<?php

return [
    'login' => [
        'identifier' => [
            'required' => 'Email or phone is required.',
        ],
        'email' => [
            'required' => 'Email is required.',
            'email'    => 'Please enter a valid email address.',
        ],
        'phone' => [
            'string' => 'Phone must be a valid string.',
        ],
        'password' => [
            'required' => 'Password is required.',
            'min'      => 'Password must be at least :min characters.',
        ],
        'failed' => 'Invalid email or password.',
    ],
];
