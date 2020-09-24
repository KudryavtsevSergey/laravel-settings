<?php

namespace Sun\Settings\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Setting;

class SettingApiController extends Controller
{
    public function get(string $key): JsonResponse
    {
        $setting = Setting::getWithLocale($key);

        return response()->json($setting);
    }

    public function getAll(): JsonResponse
    {
        $settings = Setting::getAll();
        return response()->json($settings);
    }

    public function set(Request $request, string $key): JsonResponse
    {
        $value = $request->input('value');
        Setting::setValue($key, $value);

        return $this->get($key);
    }

    public function setLocale(Request $request, string $key): JsonResponse
    {
        $value = $request->input('value');
        Setting::setLocaleValue($key, $value);

        return $this->get($key);
    }
}
