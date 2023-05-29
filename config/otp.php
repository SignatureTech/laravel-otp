<?php

return [



    /*
    |--------------------------------------------------------------------------
    | Table Name
    |--------------------------------------------------------------------------
    |
    | This option controls the name of the OTP table. you can set the table name
    | from here.
    |
    */
    'table' => env('OTP_TABLE', 'otps'),


    /*
    |--------------------------------------------------------------------------
    | OTP format
    |--------------------------------------------------------------------------
    | This option control the format of the OTP.
    | Supported: "alpha", "alphanumeric", "numeric"
    | 
    | alpha : 'a-z', 'A-Z'
    | alphanumeric : 'a-z', 'A-Z', '0-9'
    | numeric : '0-9'
    |
    */
    'format' => env('OTP_FORMAT', 'numeric'),


    /*
    |--------------------------------------------------------------------------
    | OTP characters length
    |--------------------------------------------------------------------------
    |
    | Number of characters of OTP
    |
    */
    'length' => env('OTP_LENGTH', 6),


    /*
    |--------------------------------------------------------------------------
    | OTP expiration
    |--------------------------------------------------------------------------
    |
    | Number of minutes before OTP expires
    |
    */
    'expiry' => env('OTP_EXPIRY', 10),

];
