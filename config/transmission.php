<?php

return [
    /*
     * Transmission client host
     */
    'host'         => env('TRANSMISSION_HOST'),

    /*
     * Transmission client port
     */
    'port'         => env('TRANSMISSION_PORT'),

    /*
     * Transmission client path
     */
    'path'         => env('TRANSMISSION_PATH'),

    /*
     * Indicates if transmission server is using authentication
     */
    'authenticate' => env('TRANSMISSION_AUTHENTICATE', false),

    /*
     * Transmission server username
     */
    'username'     => env('TRANSMISSION_USER'),

    /*
     * Transmission server password
     */
    'password'     => env('TRANSMISSION_PASSWORD'),
];
