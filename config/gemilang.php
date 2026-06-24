<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Guest Count Settings
    |--------------------------------------------------------------------------
    |
    | Configuration for handling extra guests beyond the standard limit.
    |
    */
    'guests' => [
        'threshold' => env('GEMILANG_GUEST_THRESHOLD', 1000),
        'charge_per_unit' => env('GEMILANG_GUEST_CHARGE', 1000000),
        'unit_size' => env('GEMILANG_GUEST_UNIT_SIZE', 100),
    ],
];
