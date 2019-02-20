<?php

if (!function_exists('loco')) {
    /**
     * Shortcut to get strings exported from LocoLaravelExport
     *
     * @param  string $key
     * @return mixed
     */
    function loco($key)
    {
        $filename = config('loco-laravel-export.lang_filename');
        return __($filename . '.' . $key);
    }
}
