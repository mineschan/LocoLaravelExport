<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Localise.biz Api Key
    |--------------------------------------------------------------------------
    |
    | Retrieve Api Key in your Loco project -> Developer Tools -> Export API -> Export key
    | Api key is bind to specific loco project.
    */
    'api_key' => env('LOCO_EXPORT_API_KEY'),


    /*
    |--------------------------------------------------------------------------
    | Language File Name
    |--------------------------------------------------------------------------
    |
    | Laravel puts language files in resources/lang, this specify the filename
    | you would like to save to your project. so you can use __({lang_filename} + $key) or helper loco($key)
    | to retrieve your translation.
    */
    'lang_filename' => env('LOCO_EXPORT_FILENAME', 'loco'),

    /*
    |--------------------------------------------------------------------------
    | Export languages
    |--------------------------------------------------------------------------
    |
    | Specify locale keys e.g. en, zh_TW, jp here.
    | Empty = export all available languages in projects
    */
    'locales' => [],


    /*
    |--------------------------------------------------------------------------
    | Download Options
    |--------------------------------------------------------------------------
    |
    | Unzipped contents will be saved to this disk and directory before copying
    | to laravel language directories.
    */
    'download' => [

        'disk' => env('LOCO_EXPORT_DOWNLOAD_DISK', 'local'),

        'directory' => 'loco_export',

        /* Whether or not you want to keep or clean the downloaded files from Loco */
        'cleanup' => false,
    ],

    /*
    |--------------------------------------------------------------------------
    | Save Options (Modify with caution)
    |--------------------------------------------------------------------------
    |
    | Laravel default resources language locations
    | This is not practical to have another location unless
    | Laravel updates change it o
    |
    */
    'export' => [
        'destination' => resource_path('lang')
    ],
];