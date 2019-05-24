<?php

namespace Sun\Settings\Http\Controllers;

use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Setting;
use Sun\Settings\Http\Requests\SettingRequest;

class SettingApiController extends Controller
{
    /**
     * @param string $key
     * @return JsonResponse
     */
    public function get(string $key)
    {
        $setting = Setting::getWithLocale($key);

        return response()->json($setting);
    }

    /**
     * @param SettingRequest $request
     * @param string $key
     * @return JsonResponse
     */
    public function set(SettingRequest $request, string $key)
    {
        $value = $request->input('value', null);
        $localeValue = $request->input('locale_value', null);

        Setting::setWithLocale($key, $value, $localeValue);

        return response()->json(['message' => 'Setting set.']);
    }
}
