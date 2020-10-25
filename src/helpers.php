<?php

use Sun\Settings\Facade;

if (!function_exists('setting')) {
    function setting($key = null, $default = null)
    {
        /** @var Setting $setting */
        $setting = app(Facade::FACADE);
        if (!is_null($key)) {
            return $setting->get($key, $default);
        }
        return $setting;
    }
}
