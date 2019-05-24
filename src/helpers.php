<?php
if (!function_exists('setting')) {
    function setting($key = null, $default = null)
    {
        $setting = app('Setting');
        if (!is_null($key)) {
            return $setting->get($key, $default);
        }
        return $setting;
    }
}
