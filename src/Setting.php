<?php

namespace Sun\Settings;

use Illuminate\Support\Collection;
use Illuminate\Support\Facades\Route;
use Sun\Locale\LocaleCache;
use Sun\Settings\SettingStorages\SettingStorage;

class Setting
{
    protected SettingStorage $storage;
    protected LocaleCache $cache;

    public function __construct(SettingStorage $storage, LocaleCache $cache)
    {
        $this->storage = $storage;
        $this->cache = $cache;
    }

    private function cacheHas(string $key): bool
    {
        return $this->cache->has($key);
    }

    private function getFromCache(string $key)
    {
        return $this->cache->get($key);
    }

    private function deleteFromCache(string $key)
    {
        $this->cache->delete($key);
    }

    private function addToCache(string $key, $value)
    {
        $this->cache->add($key, $value, config('settings.cache_time'));
    }

    private function getByKey(string $key): ?array
    {
        $cacheKey = $this->getCacheDetailKey($key);

        if ($this->cacheHas($cacheKey)) {
            return $this->getFromCache($cacheKey);
        }
        $setting = $this->storage->retrieve($key);
        $this->addToCache($cacheKey, $setting);
        return $setting;
    }

    private function getCacheDetailKey(string $key): string
    {
        return sprintf('detail_%s', $key);
    }

    private function getCacheListKey(): string
    {
        return 'all_settings_unique_cache_key';
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

        $cacheKey = $this->getCacheDetailKey($key);
        $this->deleteFromCache($cacheKey);
        $this->deleteFromCache($this->getCacheListKey());
    }

    public function getAll(): Collection
    {
        $cacheKey = $this->getCacheListKey();

        if ($this->cacheHas($cacheKey)) {
            return $this->getFromCache($cacheKey);
        }
        $settings = $this->storage->retrieveAll();

        $this->addToCache($cacheKey, $settings);
        return $settings;
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

    public static function routes($callback = null, array $options = [])
    {
        $callback = $callback ?: function (RouteRegistrar $router) {
            $router->all();
        };

        $defaultOptions = [
            'namespace' => '\Sun\Settings\Http\Controllers',
        ];

        $options = array_merge($defaultOptions, $options);

        Route::group($options, function ($router) use ($callback) {
            $callback(new RouteRegistrar($router));
        });
    }
}
