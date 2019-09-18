<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Guanyi ERP Client Environment Name
    |--------------------------------------------------------------------------
    |
    | Set the environment name to use different version of api url
    | www.guanyierp.com   =>   http://api.guanyierp.com/rest/erp_open
    | v2.guanyierp.com    =>   http://v2.api.guanyierp.com/rest/erp_open
    | demo.guanyierp.com  =>   http://api.demo.guanyierp.com/rest/erp_open
    | erp.edgj.net        =>   http://open.edgj.net/rest/erp_open
    |
    */

    'url' => env('GUANYI_API', 'http://v2.api.guanyierp.com/rest/erp_open'),



    /*
    |--------------------------------------------------------------------------
    | Authorization http request parameters
    |--------------------------------------------------------------------------
    |
    | App appkey 控制面板->应用授权->云ERP授权
    |
    */

    'appkey' => env('GUANYI_API_APP_KEY', ''),


    /*
    |--------------------------------------------------------------------------
    | Authorization http request parameters
    |--------------------------------------------------------------------------
    |
    | App secret
    |
    */
    'appsecret' => env('GUANYI_API_SECRET', ''),

    /*
    |--------------------------------------------------------------------------
    | Authorization http request parameters
    |--------------------------------------------------------------------------
    |
    | App sessionkey
    |
    */
    'sessionkey' => env('GUANYI_API_SESSION_KEY', ''),


    /*
    |--------------------------------------------------------------------------
    | Request time out
    |--------------------------------------------------------------------------
    |
    | seconds of timeout
    |
    */
    'timeout' =>  env('GUANYI_API_TIME_OUT', 2)
];
