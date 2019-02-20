<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Localise.biz Api Key
    |--------------------------------------------------------------------------
    |
    | Retrieve Api Key in Your project -> Developer Tools -> Export API -> Export key
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
    | Export Disk
    |--------------------------------------------------------------------------
    |
    | Unzipped archive will be saved to this disk.
    */
    'export_disk' => env('LOCO_EXPORT_DISK', 'local'),


    /*
    |--------------------------------------------------------------------------
    | Export Folder
    |--------------------------------------------------------------------------
    |
    | Default folder that save under desired disk.
    */
    'export_folder' => 'loco_export',


    /*
    |--------------------------------------------------------------------------
    | Clean up downloaded files after export
    |--------------------------------------------------------------------------
    |
    | Whether or not you want to keep or clean the downloaded files from Loco
    */
    'clean_up' => env('LOCO_EXPORT_CLEAN_UP', true),
];