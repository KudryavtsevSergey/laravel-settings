<?php

namespace Sun\Settings\Http\Controllers;

use Illuminate\Http\JsonResponse;
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

        Setting::setValue($key, $value);

        //TODO: localize
        return response()->json(['message' => 'Setting set.']);
    }

    public function setLocale(SettingRequest $request, string $key)
    {
        $value = $request->input('value', null);

        Setting::setLocaleValue($key, $value);

        //TODO: localize
        return response()->json(['message' => 'Setting locale set.']);
    }
}
