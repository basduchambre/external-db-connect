<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Juniper Mist credentials & base url
    |--------------------------------------------------------------------------
    |
    | Set your API credentials, can be found at https://manage.mist.com.
    | The base url is set to the Juniper Mist default but can maybe change over time
    |
    */

    'api_key' => env('JUNIPER_MIST_API_KEY', null),

    'base_url' => env('JUNIPER_MIST_BASE_URL', 'https://api.mist.com/api/v1/sites'),

    /*
    |--------------------------------------------------------------------------
    | Juniper Mist location settings
    |--------------------------------------------------------------------------
    |
    | Check the Juniper Mist API documentatiuon on how to find the site and map id
    |
    */

    'location' => [

        'site_id' => env('JUNIPER_MIST_SITE_ID', ''),

        'map_id' => env('JUNIPER_MIST_MAP_ID', ''),
        
    ],

];
