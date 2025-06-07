<?php

declare(strict_types=1);

return [
    'confirmed' => 'The :attribute confirmation does not match.',
    'email' => 'The :attribute must be a valid email address.',
    'exists' => 'The selected :attribute is invalid.',
    'required' => 'The :attribute field is required.',
    'unique' => 'The :attribute has already been taken.',
    'string' => 'The :attribute must be a string.',
    'min' => [
        'numeric' => 'The :attribute must be at least :min.',
        'string' => 'The :attribute must be at least :min characters.',
    ],
    'max' => [
        'numeric' => 'The :attribute must not be greater than :min.',
        'string' => 'The :attribute must not be greater than :min characters.',
    ],
    'integer' => 'The :attribute must be an integer.',
    'boolean' => 'The :attribute field must be true or false.',
    'password' => 'The password is incorrect.',
];
