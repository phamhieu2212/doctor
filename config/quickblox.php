<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Stripe, Mailgun, Mandrill, and others. This file provides a sane
    | default location for this type of information, allowing packages
    | to have a conventional place to find your various credentials.
    |
    */


    'auth' => [
        'application_id' => env('QUICKBLOX_APPLICATION_ID'),
        'auth_key' => env('QUICKBLOX_AUTH_KEY'),
        'auth_secret' => env('QUICKBLOX_AUTH_SECRET'),
        'account_id'  => env('QUICKBLOX_ACCOUNT_ID'),
        'account_key' => env('QUICKBLOX_ACCOUNT_KEY'),
        'account_login' => env('QUICKBLOX_ACCOUNT_LOGIN'),
        'account_password' => env('QUICKBLOX_ACCOUNT_PASSWORD'),
    ],


];
