<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Authorization http request parameters
    |--------------------------------------------------------------------------
    |
    |
    */

    'auth' => [
        'appkey' => env('GUANYI_API_APP_KEY', ''),
        'sessionkey' => env('GUANYI_API_SESSION_KEY', ''),
        'secret' => env('GUANYI_API_SECRET', '')
    ]

];
