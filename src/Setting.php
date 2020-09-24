<?php

namespace Sun\Settings;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Sun\Settings\SettingStorages\SettingStorage;

class Setting
{
    protected SettingStorage $storage;

    public function __construct(SettingStorage $storage)
    {
        $this->storage = $storage;
    }

    private function getByKey(string $key): ?array
    {
        return $this->storage->retrieve($key);
    }

    public function get(string $key, $defaultValue = null)
    {
        $defaultValue = $defaultValue ?? config("settings.default_values.{$key}");
        $setting = $this->getWithLocale($key);

        return $setting['locale_value'] ?: $setting['value'] ?: $defaultValue;
    }

    public function getWithLocale(string $key): array
    {
        return $this->getByKey($key) ?? [
                'key' => $key,
                'value' => null,
                'locale_value' => null,
            ];
    }

    public function setValue(string $key, $value = null)
    {
        $this->set($key, $value);
    }

    public function setLocaleValue(string $key, $value = null)
    {
        $this->set($key, $value, true);
    }

    public function set(string $key, $value = null, $locale = false)
    {
        $this->storage->store($key, $value, $locale);
    }

    public function getAll(): Collection
    {
        return $this->storage->retrieveAll();
    }

    public function getByKeys(array $keys): Collection
    {
        $settings = $this->getAll();

        $flippedKeys = collect($keys)->flip();

        $flippedKeys->transform(function () {
            return null;
        });

        $filteredSettings = $settings->filter(function ($setting, $key) use ($keys) {
            return in_array($key, $keys);
        });

        return $flippedKeys->merge($filteredSettings);
    }

    public static function routes(array $options = [])
    {
        $defaultOptions = [
            'namespace' => '\Sun\Settings\Http\Controllers',
        ];

        $options = array_merge($defaultOptions, $options);

        Route::group($options, function ($router) {
            (new RouteRegistrar($router))->apiRoutes();
        });
    }
}
