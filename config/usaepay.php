<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Debuging mode
    |--------------------------------------------------------------------------
    |
    | If this is turned on, detailed error messages with
    | stack traces will be shown on every error that occurs within your API request.
    |
    */

    'debug' => env('USAEPAY_DEBUG', false),

    /*
    |--------------------------------------------------------------------------
    | USAePay Source Key
    |--------------------------------------------------------------------------
    |
    | Each merchant will be provided with source key as API authentication.
    |
    */

    'key' => env('USAEPAY_KEY'),

    /*
    |--------------------------------------------------------------------------
    | USAePay Pin (optional)
    |--------------------------------------------------------------------------
    |
    | Sometimes the source key is paired with pin for more secured auth.
    |
    */

    'pin' => env('USAEPAY_PIN'),

    /*
    |--------------------------------------------------------------------------
    | Environment Mode
    |--------------------------------------------------------------------------
    |
    | Determines whether to use sandbox or live usaepay connection.
    |
    */

    'sandbox' => env('USAEPAY_SANDBOX', false),

    /*
    |--------------------------------------------------------------------------
    | USAePay Servers
    |--------------------------------------------------------------------------
    |
    | Here you may define all server endpoints for automatic healthy server switching.
    |
    */

    'server' => [
        'main' => 'www.usaepay.com',
        'sandbox' => 'sandbox.usaepay.com',
        'alternate1' => 'www-01.usaepay.com',
        'alternate2' => 'www-02.usaepay.com'
    ],

    /*
    |--------------------------------------------------------------------------
    | Connection Protocol
    |--------------------------------------------------------------------------
    |
    | Here you may define the default connection protocol use to every request.
    |
    */

    'proto' => 'https',

    /*
    |--------------------------------------------------------------------------
    | WSDL uri
    |--------------------------------------------------------------------------
    |
    | Set the uri relative to the server.
    |
    */

    // 'wsdl' => '/soap/gate/9FA9CF37/usaepay.wsdl',
    'wsdl' => '/soap/gate/0AE595C1/usaepay.wsdl',

    /*
    |--------------------------------------------------------------------------
    | USAePay ueSecurityToken encryption type.
    |--------------------------------------------------------------------------
    |
    | Supported Methods: 'sha1' and 'md5'
    |
    */

    'encryption' => 'sha1',
    
];
