<?php

return [

    /*
    |--------------------------------------------------------------------------
    | GST Portal Credentials
    |--------------------------------------------------------------------------
    | All credentials for GST portal are stored here and pulled from .env file.
    | This keeps sensitive data out of the codebase.
    */

    'ip_address'        => env('GST_PORTAL_IP', '127.0.0.1'),
    'client_id'         => env('GST_PORTAL_CLIENT_ID'),
    'client_secret'     => env('GST_PORTAL_CLIENT_SECRET'),
    'gstin'             => env('GST_PORTAL_GSTIN'),
    'email'             => env('GST_PORTAL_EMAIL'),
    'username'          => env('GST_PORTAL_USERNAME'),
    'password'          => env('GST_PORTAL_PASSWORD'),
    'supervisor_id'     => env('GST_PORTAL_SUPERVISOR_ID', null),
    'gst_base_url'     => env('GST_PORTAL_BASE_URL', 'https://apisandbox.whitebooks.in'),

];
